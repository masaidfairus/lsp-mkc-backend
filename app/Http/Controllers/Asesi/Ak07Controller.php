<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Http\Controllers\Controller;
use App\Models\AssessmentPlanning;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak07Controller extends Controller
{
    public function show(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if ($registration->status_apl01 !== Apl01Status::APPROVED) {
            return $this->errorResponse('E2009', 403);
        }

        if ($registration->status_apl02 !== Apl02Status::APPROVED) {
            return $this->errorResponse('E2018', 403);
        }

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_name' => $registration->candidate->name,
            'assessor_name' => $registration->assessor->name,
            'tuk' => $registration->tuk,
            'scheme_certification' => [
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
            'ak07_data' => ['required', 'array'],

            // Skema Sertifikasi
            'ak07_data.certification_scheme' => ['required', 'array'],
            'ak07_data.certification_scheme.name' => ['required', 'string'],
            'ak07_data.certification_scheme.code' => ['required', 'string'],

            // Informasi Umum
            'ak07_data.tuk' => ['required', 'string'],
            'ak07_data.asesor_name' => ['required', 'string'],
            'ak07_data.asesi_name' => ['required', 'string'],
            'ak07_data.assessment_date' => ['required', 'date'],

            // Potensi Asesi
            'ak07_data.potential' => ['required', 'array'],
            'ak07_data.potential.*.label' => ['required', 'string'],
            'ak07_data.potential.*.is_checked' => ['required', 'boolean'],

            // Part A
            'ak07_data.part_a' => ['required', 'array'],
            'ak07_data.part_a.*.id' => ['required', 'integer'],
            'ak07_data.part_a.*.title' => ['required', 'string'],
            'ak07_data.part_a.*.needs_adjustment' => ['required', 'boolean'],
            'ak07_data.part_a.*.note' => ['array'],
            'ak07_data.part_a.*.note.*.title' => ['required', 'string'],
            'ak07_data.part_a.*.note.*.is_checked' => ['required', 'boolean'],
            'ak07_data.part_a.*.note.*.custom_value' => ['nullable', 'string'],

            // Part B
            'ak07_data.part_b' => ['required', 'array'],
            'ak07_data.part_b.*.id' => ['required', 'integer'],
            'ak07_data.part_b.*.question' => ['required', 'string'],
            'ak07_data.part_b.*.answer' => ['required', 'boolean'],
            'ak07_data.part_b.*.adjustment_decisions' => ['required', 'array'],
            'ak07_data.part_b.*.adjustment_decisions.industry_standard' => ['nullable', 'string'],
            'ak07_data.part_b.*.adjustment_decisions.sop' => ['nullable', 'string'],
            'ak07_data.part_b.*.adjustment_decisions.technical_regulation' => ['nullable', 'string'],
            'ak07_data.part_b.*.adjustment_decisions.assessment_method' => ['nullable', 'string'],
            'ak07_data.part_b.*.adjustment_decisions.fr_ia_assessment_instrument' => ['nullable', 'string'],

            // Adjustment Results
            'ak07_data.adjustment_results' => ['required', 'array'],
            'ak07_data.adjustment_results.assessment_benchmark' => ['nullable', 'string'],
            'ak07_data.adjustment_results.assessment_method' => ['nullable', 'string'],
            'ak07_data.adjustment_results.assessment_instrument' => ['nullable', 'string'],

            // Signatures
            'ak07_data.signatures' => ['required', 'array'],
            'ak07_data.signatures.asesor' => ['required', 'array'],
            'ak07_data.signatures.asesor.name' => ['required', 'string'],
            'ak07_data.signatures.asesor.date' => ['required', 'date'],
            'ak07_data.signatures.asesor.signature' => ['required', 'string'],

            'ak07_data.signatures.asesi' => ['required', 'array'],
            'ak07_data.signatures.asesi.name' => ['required', 'string'],
            'ak07_data.signatures.asesi.date' => ['required', 'date'],
            'ak07_data.signatures.asesi.signature' => ['required', 'string'],
        ]);

        AssessmentPlanning::updateOrCreate(
            ['registration_id' => $registration->id],
            ['ak07_data' => $validated['ak07_data']]
        );

        return $this->successResponse(
            $validated['ak07_data'],
            'Data AK.07 berhasil disimpan'
        );
    }
}
