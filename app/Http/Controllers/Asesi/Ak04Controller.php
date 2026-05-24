<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\AssessmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak04Controller extends Controller
{
    /**
     * Menampilkan detail dari resource yang ditentukan.
     *
     * @param  AssessmentRegistration  $registration
     * @return mixed
     */
    public function show(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if (!$registration->status->isApl01Complete()) {
            return $this->errorResponse('E2009', 403);
        }

        if (!$registration->status->isApl02Complete()) {
            return $this->errorResponse('E2018', 403);
        }

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_name' => $registration->candidate->name,
            'assessor_name' => $registration->assessor->name,
            "appeal_questions" => [
                [
                    "id" => 1,
                    "label" => "Apakah Proses Banding telah dijelaskan kepada Anda?",
                ],
                [
                    "id" => 2,
                    "label" => "Apakah Anda telah mendiskusikan Banding dengan Asesor?",
                ],
                [
                    "id" => 3,
                    "label" => 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?'
                ]
            ],
            "scheme_certification" => [
                'id' => $registration->scheme->id,
                'code' => $registration->scheme->code,
                'name' => $registration->scheme->name,
            ],
            "saved_data" => $registration->appeals->first()?->ak04_data
        ]);
    }

    /**
     * Menyimpan resource baru ke dalam penyimpanan.
     *
     * @param  Request  $request
     * @param  AssessmentRegistration  $registration
     * @return mixed
     */
    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $validated = $request->validate([
            'ak04_data' => ['required', 'array'],

            // Nama & Tanggal
            'ak04_data.asesi_name' => ['required', 'string'],
            'ak04_data.asesor_name' => ['required', 'string'],
            'ak04_data.assessment_date' => ['required', 'date'],

            // Appeal Questions
            'ak04_data.appeal_questions' => ['required', 'array', 'min:1'],
            'ak04_data.appeal_questions.*.id' => ['required', 'integer'],
            'ak04_data.appeal_questions.*.label' => ['required', 'string'],
            'ak04_data.appeal_questions.*.answer' => ['required', 'boolean'],

            // Appeal Target
            'ak04_data.appeal_target' => ['required', 'array'],
            'ak04_data.appeal_target.scheme_code' => ['required', 'string'],
            'ak04_data.appeal_target.scheme_name' => ['required', 'string'],

            // Reason Notes
            'ak04_data.reason_notes' => ['required', 'string'],

            // Signature
            'ak04_data.signature' => ['required', 'array'],
            'ak04_data.signature.asesi_signature' => ['required', 'string'],
            'ak04_data.signature.date' => ['required', 'date'],
        ]);

        $ak04Data = $validated['ak04_data'];
        
        // --- Signature Processing ---
        if (isset($ak04Data['signature']['asesi_signature'])) {
            $base64Image = $ak04Data['signature']['asesi_signature'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            }
            $image = base64_decode($base64Image);
            $user = auth()->user();
            $sigName = "sig_user_{$user->id}_ak04_" . uniqid() . ".png";
            $sigPath = "signatures/" . $sigName;
            \Illuminate\Support\Facades\Storage::disk('local')->put($sigPath, $image);
            
            $qrText = route('signature.verify', ['id' => $registration->id, 'type' => 'ak04']);
            $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($qrText));
            
            $ak04Data['signature']['asesi_signature'] = $sigPath;
            $ak04Data['signature']['qr_code'] = $qrBase64;
            $ak04Data['signature']['date'] = $ak04Data['signature']['date'] ?? now()->toDateTimeString();
        }

        Appeal::updateOrCreate(
            ['registration_id' => $registration->id],
            [
                'ak04_data' => $ak04Data,
                'status' => \App\Enums\AppealStatus::PENDING,
                'asesi_signature' => $ak04Data['signature']['asesi_signature'] ?? null,
                'asesi_qr_code' => $ak04Data['signature']['qr_code'] ?? null,
                'asesi_signature_date' => $ak04Data['signature']['date'] ?? now(),
            ]
        );

        return $this->successResponse(
            $validated['ak04_data'],
            'Data AK.04 berhasil disimpan'
        );
    }

    /**
     * Asesi membatalkan pengajuan AK.04 yang masih pending.
     */
    public function destroy(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $appeal = Appeal::where('registration_id', $registration->id)->first();

        if (!$appeal) {
            return response()->json(['message' => 'Data AK.04 tidak ditemukan'], 404);
        }

        // Hanya boleh dibatalkan jika masih PENDING (belum diverifikasi admin)
        if ($appeal->status !== \App\Enums\AppealStatus::PENDING) {
            return response()->json([
                'message' => 'AK.04 yang sudah diverifikasi tidak dapat dibatalkan'
            ], 403);
        }

        $appeal->delete();

        return response()->json(['message' => 'AK.04 berhasil dibatalkan']);
    }
}
