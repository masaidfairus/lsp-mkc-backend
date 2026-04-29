<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Carbon\Carbon;

abstract class Controller
{
    //
    use ApiResponse;

    public function convertDateToLocalTime($rawDate)
    {
        return Carbon::parse($rawDate)
            ->locale('id')
            ->settings(['formatFunction' => 'translatedFormat'])
            ->format('l, d F Y');
    }

    public function getAssessorSignatureData($assessor)
    {
        if (!$assessor) {
            return [
                'name' => '-',
                'reg_number' => '-',
                'qr_code' => null,
            ];
        }

        $sig = \Illuminate\Support\Facades\DB::table('user_signatures')
            ->where('user_id', $assessor->id)
            ->orWhere('name', $assessor->name)
            ->latest('created_at')
            ->first();

        return [
            'name' => $assessor->name,
            'reg_number' => $assessor->registration_number ?? '-',
            'qr_code' => $sig ? $sig->qr_code : null,
        ];
    }
}
