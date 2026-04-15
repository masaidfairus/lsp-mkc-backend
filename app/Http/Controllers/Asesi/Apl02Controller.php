<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Enums\PaymentStatus;
use App\Models\Apl02Portfolio;
use App\Models\AssessmentRegistration;
use App\Models\DocumentList;
use App\Models\FileProfile;
use App\Models\PortfolioScheme;
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

        $validListIds = PortfolioScheme::where('scheme_id', $registration->scheme_id)
            ->pluck('list_id')
            ->toArray();

        $portfolios = FileProfile::where('user_id', request()->user()->id)
            ->whereIn('list_id', $validListIds)
            ->with('documentList')
            ->get()
            ->map(function ($f) use ($registration) {
                $apl02Portfolio = Apl02Portfolio::where([
                    'registration_id' => $registration->id,
                    'file_profile_id' => $f->id,
                ])->first();

                $f->is_active = $apl02Portfolio ? $apl02Portfolio->is_active : true;
                return $f;
            });

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_type' => $registration->candidate->profile->asesi_type,
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

        $user = $registration->candidate;

        $validated = $request->validate([
            'is_declared' => ['required', 'accepted'],
            'portfolio_ids' => ['nullable', 'array'],
            'portfolio_ids.*' => ['integer', 'exists:file_profiles,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.element_id' => ['required', 'integer'],
            'answers.*.is_competent' => ['required', 'boolean'],
        ]);

        $mandatoryIds = $registration->scheme->portfolios
            ->where('is_mandatory', true)
            ->pluck('list_id');

        if ($mandatoryIds->isNotEmpty()) {
            $userFileIds = $user->userFile->pluck('list_id');

            $missingFiles = $mandatoryIds->diff($userFileIds);

            if ($missingFiles->isNotEmpty()) {
                $missingFilesName = DocumentList::whereIn('id', $missingFiles)->pluck('title');

                return $this->errorResponse('E2013', 403, [
                    'missing_files' => $missingFilesName,
                ]);
            }
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
            Apl02Portfolio::where('registration_id', $registration->id)->delete();
            foreach ($validated['portfolio_ids'] ?? [] as $fileProfileId) {
                Apl02Portfolio::create([
                    'registration_id' => $registration->id,
                    'file_profile_id' => $fileProfileId,
                    'is_active' => true,
                ]);
            }

            $rows = array_map(fn($answer) => [
                'registration_id' => $registration->id,
                'element_id' => $answer['element_id'],
                'is_competent' => $answer['is_competent'],
                'created_at' => now(),
                'updated_at' => now(),
            ], $validated['answers']);

            SelfAssessment::upsert(
                $rows,
                ['registration_id', 'element_id'],
                ['is_competent', 'updated_at']
            );

            $registration->update(['status_apl02' => Apl02Status::SUBMITTED]);
        });

        return $this->successResponse(
            ['total_answers' => count($validated['answers'])],
            'APL.02 berhasil disubmit.'
        );
    }

    public function toogleActive(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $validated = $request->validate([
            "file_profile_id" => ['required', 'integer'],
            "is_active" => ['required', 'boolean'],
        ]);

        $updated = Apl02Portfolio::where('registration_id', $registration->id)
            ->where('file_profile_id', $validated['file_profile_id'])
            ->update([
                'is_active' => $validated['is_active']
            ]);

        if (!$updated) {
            return $this->errorResponse('E2016', 404);
        }

        return $this->successResponse([], 'Status berhasil diperbarui');
    }
}
