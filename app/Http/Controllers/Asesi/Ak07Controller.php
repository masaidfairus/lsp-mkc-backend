<?php

namespace App\Http\Controllers\Asesi;

use App\Enums\AssessmentStatus;
use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use Illuminate\Http\Request;

class Ak07Controller extends Controller
{
    /**
     * GET /api/registrations/{registration}/ak07
     * Returns the full ak07_data for the given registration.
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

        $record = $registration->plannings->first();

        if (!$record) {
            return $this->errorResponse('E2020', 404);
        }

        return $this->successResponse($record->ak07_data);
    }

    /**
     * POST /api/registrations/{registration}/ak07
     * Asesi submits only their signature from the mobile device.
     */
    public function store(Request $request, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if (!$registration->status->isApl01Complete()) {
            return $this->errorResponse('E2009', 403);
        }

        if (!$registration->status->isApl02Complete()) {
            return $this->errorResponse('E2018', 403);
        }

        $validated = $request->validate([
            'name'      => ['required', 'string'],
            'date'      => ['required', 'date'],
            'signature' => ['required', 'string'],
        ]);

        $record = $registration->plannings->first();

        if (!$record) {
            return $this->errorResponse('E2020', 404);
        }

        // Patch only the asesi signature — all other ak07_data written by admin panel
        $data = $record->ak07_data ?? [];

        $data['signatures']['asesi'] = [
            'name'      => $validated['name'],
            'date'      => $validated['date'],
            'signature' => $validated['signature'],
        ];

        $record->ak07_data = $data;
        $record->save();

        return $this->successResponse(
            $record->ak07_data,
            'Tanda tangan AK.07 berhasil disimpan'
        );
    }
}
