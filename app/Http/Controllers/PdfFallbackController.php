<?php

namespace App\Http\Controllers;

use App\Models\AssessmentRegistration;

class PdfFallbackController extends Controller
{
    public function show(AssessmentRegistration $registration, $title = 'Belum Dapat Dicetak', $message = 'Dokumen ini belum dapat dicetak karena data belum lengkap atau belum diisi oleh Asesor/Admin.')
    {
        return view('components.status-page-fallback', [
            'title' => $title,
            'message' => $message,
            'status' => 'menunggu', // untuk warna icon
            'registration' => $registration,   
        ]);
    }
}