<?php

namespace App\Http\Controllers;

use App\Models\AssessmentRegistration;

class PdfFallbackController extends Controller
{
    public function show($registration, $title = 'Dokumen Belum Siap', $message = 'Dokumen belum dapat dicetak.')
    {
        $isSignatureError = str_contains(strtolower($title), 'tanda tangan') || str_contains(strtolower($message), 'tanda tangan');
        
        return view('pdf.fallback', [
            'registration' => $registration,
            'title' => $title,
            'message' => $message,
            'isSignatureError' => $isSignatureError,
            'icon' => $isSignatureError ? '✍️' : '⌛'
        ]);
    }
}