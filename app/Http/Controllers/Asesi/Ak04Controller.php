<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\AssessmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak04Controller extends Controller
{
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
        ]);
    }

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

        Appeal::updateOrCreate(
            ['registration_id' => $registration->id],
            ['ak04_data' => $validated['ak04_data']]
        );

        return $this->successResponse(
            $validated['ak04_data'],
            'Data AK.04 berhasil disimpan'
        );
    }
}
