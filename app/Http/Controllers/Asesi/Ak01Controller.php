<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak01Controller extends Controller
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

        if (!$registration->assessor_id) {
            return $this->errorResponse('E2019', 403);
        }

        return $this->successResponse([
            $registration->plannings->first()->ak01_data
        ]);
    }
    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string'],
            'date' => ['required', 'date'],
            'signature' => ['required', 'string'],
        ]);

        $record = $registration->plannings->first();
        $data = $record->ak01_data ?? [];

        $data['signatures']['asesi'] = [
            'name' => $validated['name'],
            'date' => $validated['date'],
            'signature' => $validated['signature'],
        ];

        $record->ak01_data = $data;
        $record->save();

        return $this->successResponse(
            $record,
            'Data AK.01 berhasil disimpan'
        );
    }
}
