<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Daftar ujian yang tersedia untuk Asesi.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $registrations = AssessmentRegistration::where('user_id', $user->id)
            ->pluck('id');

        if ($registrations->isEmpty()) {
            return $this->successResponse([
                'pending' => [],
                'completed' => [],
                'all' => [],
            ]);
        }

        $examForms = AssessmentInstrument::whereIn('registration_id', $registrations)
            ->whereIn('document_type', [
                'FR.IA.05A',
                'FR.IA.06A',
            ])
            ->with('registration.scheme')
            ->get();

        // Ambil semua form C yang sudah dikerjakan dalam satu query beserta signature_requests
        $formCs = AssessmentInstrument::whereIn('registration_id', $registrations)
            ->whereIn('document_type', ['FR.IA.05C', 'FR.IA.06C'])
            ->with(['signatureRequests'])
            ->get()
            ->keyBy(function ($item) {
                return $item->registration_id . '_' . $item->document_type;
            });

        $exams = [];

        foreach ($examForms as $formA) {
            $typeC = str_replace('A', 'C', $formA->document_type);
            $keyC = $formA->registration_id . '_' . $typeC;
            
            $formC = $formCs->get($keyC);
            $mainData = is_array($formA->main_data) ? $formA->main_data : json_decode($formA->main_data ?? '{}', true);

            $questionsCount = 0;
            if ($formA->document_type === 'FR.IA.05A') {
                $questionsCount = count(data_get($mainData, 'questions_list', []));
            } elseif ($formA->document_type === 'FR.IA.06A') {
                $questionsCount = count(data_get($mainData, 'questions_list', []));
            }

            $status = 'pending';
            $scoreData = null;

            if ($formC) {
                $status = 'menunggu_asesor';
                
                // Cek status TTD Asesor
                $asesorReq = $formC->signatureRequests->where('role', 'Asesor')->first();
                if ($asesorReq && $asesorReq->status === 'signed') {
                    $status = 'menunggu_asesi';
                    
                    // Cek status TTD Asesi
                    $asesiReq = $formC->signatureRequests->where('role', 'asesi')->first();
                    if ($asesiReq && $asesiReq->status === 'signed') {
                        $status = 'completed';
                    }
                }

                // Kalkulasi skor (benar/salah) khusus jika FR.IA.05C (PG)
                if ($formA->document_type === 'FR.IA.05A') {
                    $questions = data_get($mainData, 'questions_list', []);
                    $formCMainData = is_array($formC->main_data) ? $formC->main_data : json_decode($formC->main_data ?? '{}', true);
                    $answers = data_get($formCMainData, 'answer_sheet', []);
                    
                    $correct = 0;
                    $wrong = 0;
                    foreach ($questions as $q) {
                        $no = data_get($q, 'question_no');
                        $key = data_get($q, 'answer_key');
                        
                        $ans = collect($answers)->firstWhere('no', $no);
                        $userAns = data_get($ans, 'answer');
                        
                        // Bandingkan jawaban dengan kunci (case insensitive)
                        if (strtolower(trim($userAns ?? '')) === strtolower(trim($key ?? ''))) {
                            $correct++;
                        } else {
                            $wrong++;
                        }
                    }
                    $scoreData = [
                        'correct' => $correct,
                        'wrong' => $wrong,
                        'total' => count($questions),
                        'score' => count($questions) > 0 ? round(($correct / count($questions)) * 100) : 0,
                    ];
                }
            }

            $exams[] = [
                'id' => $formA->id,
                'registration_id' => $formA->registration_id,
                'document_type' => $formA->document_type,
                'scheme_name' => $formA->registration?->scheme?->name ?? '-',
                'title' => $formA->document_type === 'FR.IA.05A'
                    ? 'Daftar Pertanyaan Tertulis Pilihan Ganda'
                    : 'Daftar Pertanyaan Tertulis Esai',
                'questions_count' => $questionsCount,
                'status' => $status,
                'score_data' => $scoreData,
                'created_at' => $formA->created_at?->toISOString(),
            ];
        }

        $collection = collect($exams);

        return $this->successResponse([
            'pending' => $collection->where('status', 'pending')->values(),
            'completed' => $collection->where('status', 'completed')->values(),
            'all' => $exams,
        ]);
    }

    /**
     * Detail soal ujian.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $formA = AssessmentInstrument::with('registration')
            ->where('id', $id)
            ->whereIn('document_type', ['FR.IA.05A', 'FR.IA.06A'])
            ->first();

        if (!$formA) {
            return response()->json([
                'metadata' => ['code' => 404, 'message' => 'Ujian tidak ditemukan'],
                'response' => [],
            ], 404);
        }

        $reg = $formA->registration;
        if (!$reg || $reg->user_id !== $user->id) {
            return response()->json([
                'metadata' => ['code' => 403, 'message' => 'Akses ditolak'],
                'response' => [],
            ], 403);
        }

        $mainData = is_array($formA->main_data) ? $formA->main_data : json_decode($formA->main_data ?? '{}', true);

        $questions = [];
        if ($formA->document_type === 'FR.IA.05A') {
            // Pilihan Ganda
            foreach (data_get($mainData, 'questions_list', []) as $q) {
                $options = [];
                foreach (data_get($q, 'options', []) as $opt) {
                    $options[] = [
                        'label' => data_get($opt, 'label', ''),
                        'text' => data_get($opt, 'option_text', ''),
                    ];
                }
                $questions[] = [
                    'no' => data_get($q, 'question_no'),
                    'question' => data_get($q, 'question_text', ''),
                    'type' => 'multiple_choice',
                    'options' => $options,
                ];
            }
        } elseif ($formA->document_type === 'FR.IA.06A') {
            // Esai
            foreach (data_get($mainData, 'questions_list', []) as $q) {
                $questions[] = [
                    'no' => data_get($q, 'no'),
                    'question' => data_get($q, 'question_text', ''),
                    'type' => 'essay',
                    'options' => [],
                ];
            }
        }

        return $this->successResponse([
            'id' => $formA->id,
            'document_type' => $formA->document_type,
            'title' => $formA->document_type === 'FR.IA.05A'
                ? 'Daftar Pertanyaan Tertulis Pilihan Ganda'
                : 'Daftar Pertanyaan Tertulis Esai',
            'header' => data_get($mainData, 'header', []),
            'questions' => $questions,
        ]);
    }

    /**
     * Submit jawaban ujian dari Asesi.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request, $id)
    {
        $user = $request->user();
        
        $formA = AssessmentInstrument::with('registration')
            ->where('id', $id)
            ->whereIn('document_type', ['FR.IA.05A', 'FR.IA.06A'])
            ->first();

        if (!$formA) {
            return response()->json([
                'metadata' => ['code' => 404, 'message' => 'Ujian tidak ditemukan'],
                'response' => [],
            ], 404);
        }

        $reg = AssessmentRegistration::with(['scheme', 'assessor', 'candidate'])->find($formA->registration_id);
        if (!$reg || $reg->user_id !== $user->id) {
            return response()->json([
                'metadata' => ['code' => 403, 'message' => 'Akses ditolak'],
                'response' => [],
            ], 403);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
        ]);

        $typeC = str_replace('A', 'C', $formA->document_type);

        // Cek apakah jawaban sudah pernah dikirim
        $existingFormC = AssessmentInstrument::where('registration_id', $formA->registration_id)
            ->where('document_type', $typeC)
            ->first();

        if ($existingFormC) {
            return response()->json([
                'metadata' => ['code' => 400, 'message' => 'Anda sudah mengirimkan jawaban untuk ujian ini'],
                'response' => [],
            ], 400);
        }

        // Build answer_sheet sesuai format Filament resource
        $answerSheet = [];
        foreach ($validated['answers'] as $i => $answer) {
            if ($formA->document_type === 'FR.IA.05A') {
                // Pilihan Ganda → FR.IA.05C
                $answerSheet[] = [
                    'no' => $i + 1,
                    'answer' => data_get($answer, 'answer', ''),
                    'achievement' => ['is_yes' => null],
                ];
            } else {
                // Esai → FR.IA.06C
                $answerSheet[] = [
                    'no' => $i + 1,
                    'answer_text' => data_get($answer, 'answer_text', ''),
                    'achievement' => ['is_yes' => null],
                ];
            }
        }

        $mainData = [
            'header' => [
                'certification_scheme' => $reg->scheme?->name,
                'scheme_number' => $reg->scheme?->code,
                'tuk' => 'Sewaktu/Tempat Kerja/Mandiri',
                'assessor_name' => $reg->assessor?->name,
                'assessee_name' => $reg->candidate?->name,
                'date' => now()->format('Y-m-d'),
                'time' => now()->format('H:i'),
            ],
            'answer_sheet' => $answerSheet,
        ];

        $formC = AssessmentInstrument::create([
            'registration_id' => $formA->registration_id,
            'document_type' => $typeC,
            'main_data' => $mainData,
        ]);

        // Buat SignatureRequest untuk Asesor (Menunggu TTD Asesor)
        // Karena Form A tidak spawn TTD Asesor di awal, maka di spawn ketika Form C dibuat Asesi.
        if ($reg->assessor_id) {
            \App\Models\SignatureRequest::create([
                'registration_id' => $formC->registration_id,
                'user_id'         => $reg->assessor_id,
                'document_label'  => str_replace('FR.', '', $typeC),
                'status'          => 'pending',
                'signable_type'   => get_class($formC),
                'signable_id'     => $formC->id,
                'role'            => 'Asesor',
            ]);
        }

        return $this->successResponse([
            'id' => $formC->id,
            'document_type' => $typeC,
        ], 'Jawaban berhasil disimpan');
    }
}
