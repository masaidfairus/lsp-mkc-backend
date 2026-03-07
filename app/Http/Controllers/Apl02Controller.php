<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class APL02Controller extends Controller
{
    public function generate(Request $request)
    {
        $data = [
            'nama_skema'     => $request->nama_skema,
            'kode_skema'     => $request->kode_skema,
            'nama_asesi'     => strtoupper($request->nama_asesi),
            'nama_asesor'    => $request->nama_asesor,
            'no_reg_asesor'  => $request->no_reg_asesor,
            'unit_kompetensi'=> $request->unit_kompetensi ?? [],
        ];

        $pdf = Pdf::loadView('forms.apl02', $data)->setPaper('a4', 'portrait');
        $filename = 'APL-02_' . str_replace(' ', '_', $request->nama_asesi) . '_' . date('Ymd') . '.pdf';

        return $pdf->stream($filename);
    }
}