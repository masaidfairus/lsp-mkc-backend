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
}
