<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Ak03;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak03Controller extends Controller
{
    /**
     * GET /api/registrations/{registration}/ak03
     */
    public function show(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $record = Ak03::where('registration_id', $registration->id)->first();

        // If record exists, return its main_data
        if ($record) {
            return $this->successResponse($record->main_data);
        }

        // Otherwise return default template with master data
        $data = [
            'header' => [
                'certification_scheme' => $registration->scheme?->name ?? '',
                'scheme_code'          => $registration->scheme?->code ?? '',
                'tuk'                  => $registration->tuk ?? '',
                'assessor_name'        => $registration->assessor?->name ?? '',
                'assessee_name'        => $registration->candidate?->name ?? '',
                'assessment_date_start' => now()->toDateString(),
                'assessment_date_end'   => now()->toDateString(),
            ],
            'feedback_items' => $this->defaultFeedbackItems(),
            'additional_footer' => [
                'other_comments' => '',
            ],
        ];

        return $this->successResponse($data);
    }

    /**
     * POST /api/registrations/{registration}/ak03
     */
    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $validated = $request->validate([
            'main_data' => ['required', 'array'],
        ]);

        $record = Ak03::updateOrCreate(
            ['registration_id' => $registration->id],
            ['main_data' => $validated['main_data']]
        );

        return $this->successResponse(
            $record->main_data,
            'Formulir AK.03 berhasil disimpan'
        );
    }

    private function defaultFeedbackItems(): array
    {
        return [
            ['component' => 'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
            ['component' => 'Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen', 'result_yes' => null, 'result_no' => null, 'assessee_note' => ''],
        ];
    }
}
