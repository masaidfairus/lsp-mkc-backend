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

        return $this->successResponse($registration->plannings->first()->ak07_data);
    }

    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if ($registration->status_apl01 !== Apl01Status::APPROVED) {
            return $this->errorResponse('E2009', 403);
        }

        if ($registration->status_apl02 !== Apl02Status::APPROVED) {
            return $this->errorResponse('E2018', 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'signature' => ['required', 'string'],
        ]);

        $record = $registration->plannings->first();
        $data = $record->ak07_data ?? [];

        $data['signatures']['asesi'] = [
            'name' => $validated['name'],
            'date' => $validated['date'],
            'signature' => $validated['signature'],
        ];

        $record->ak07_data = $data;
        $record->save();

        return $this->successResponse(
            $record->ak07_data,
            'Data AK.07 berhasil disimpan'
        );
    }
}
