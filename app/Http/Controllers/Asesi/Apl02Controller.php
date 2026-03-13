<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Enums\PaymentStatus;
use App\Models\Apl02Portfolio;
use App\Models\AssessmentRegistration;
use App\Models\Portfolio;
use App\Models\SelfAssessment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Apl02Controller extends Controller
{
    use AuthorizesRequests;

    public function form(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if ($registration->status_apl01 !== Apl01Status::APPROVED) {
            return $this->errorResponse('E2009', 403);
        }

        if ($registration->asesi_type === 'pribadi') {
            $payment = $registration->payments()->first();
            if (!$payment || $payment->status !== PaymentStatus::PAID) {
                return $this->errorResponse('E2010', 403);
            }
        }

        $scheme = $registration->scheme->load([
            'competencyUnits.competencyElements.performanceCriteria'
        ]);

        $portfolios = Portfolio::where('user_id', request()->user()->id)
            ->where(function ($q) use ($registration) {
                $q->where('scheme_id', $registration->scheme_id)
                    ->orWhereNull('scheme_id');
            })
            ->get()
            ->map(function ($portfolio) use ($registration) {
                $apl02Portfolio = Apl02Portfolio::where([
                    'registration_id' => $registration->id,
                    'portfolio_id' => $portfolio->id,
                ])->first();

                $portfolio->is_active = $apl02Portfolio
                    ? $apl02Portfolio->is_active
                    : true;
                return $portfolio;
            });

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_type' => $registration->asesi_type,
            'portfolios' => $portfolios,
            'scheme' => [
                'id' => $scheme->id,
                'code' => $scheme->code,
                'name' => $scheme->name,
                'units' => $scheme->competencyUnits->map(fn($unit) => [
                    'id' => $unit->id,
                    'unit_code' => $unit->unit_code,
                    'unit_title' => $unit->unit_title,
                    'elements' => $unit->competencyElements->map(fn($element) => [
                        'id' => $element->id,
                        'title' => $element->title,
                        'criteria' => $element->performanceCriteria->map(fn($criterion) => [
                            'id' => $criterion->id,
                            'name' => $criterion->name,
                            'description' => $criterion->description,
                        ]),
                    ]),
                ]),
            ],
        ]);
    }
    public function submit(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $validated = $request->validate([
            'is_declared' => ['required', 'accepted'],
            'portfolio_ids' => ['nullable', 'array'],
            'portfolio_ids.*' => ['integer', 'exists:portfolios,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.element_id' => ['required', 'integer'],
            'answers.*.is_competent' => ['required', 'boolean'],
        ]);

        Apl02Portfolio::where('registration_id', $registration->id)->delete();
        foreach ($validated['portfolio_ids'] as $pid) {
            Apl02Portfolio::create([
                'registration_id' => $registration->id,
                'portfolio_id' => $pid,
                'is_active' => true,
            ]);
        }

        foreach ($validated['answers'] as $answer) {
            SelfAssessment::updateOrCreate(
                [
                    'registration_id' => $registration->id,
                    'element_id' => $answer['element_id']
                ],
                ['is_competent' => $answer['is_competent']]
            );
        }

        $validElementIds = $registration->scheme
            ->competencyUnits()
            ->with('competencyElements:id,competency_unit_id')
            ->get()
            ->pluck('competencyElements')
            ->flatten()
            ->pluck('id')
            ->toArray();

        $submittedElementIds = array_column($validated['answers'], 'element_id');
        $invalidIds = array_diff($submittedElementIds, $validElementIds);
        $missingIds = array_diff($validElementIds, $submittedElementIds);

        if (!empty($invalidIds)) {
            return $this->errorResponse('E2011', 422);
        }

        if (!empty($missingIds)) {
            return $this->errorResponse('E2012', 422);
        }

        DB::transaction(function () use ($validated, $registration) {
            $rows = array_map(fn($answer) => [
                'registration_id' => $registration->id,
                'element_id' => $answer['element_id'],
                'is_competent' => $answer['is_competent'],
                'portfolio_ids' => json_encode($answer['portfolio_ids'] ?? []),
                'created_at' => now(),
                'updated_at' => now(),
            ], $validated['answers']);

            SelfAssessment::upsert(
                $rows,
                ['registration_id', 'element_id'],
                ['is_competent', 'portfolio_ids', 'updated_at']
            );

            $registration->update(['status_apl02' => Apl02Status::SUBMITTED]);
        });

        return $this->successResponse(
            ['total_answers' => count($validated['answers'])],
            'APL.02 berhasil disubmit.'
        );
    }
}
