<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class APL01Controller extends Controller
{
    public function index()
    {
        return view('apl01.form');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'nik'            => 'required|string|max:20',
            'tempat_lahir'   => 'required|string|max:50',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'agama'          => 'required|string|max:20',
            'no_telp'        => 'required|string|max:15',
            'email'          => 'required|email',
            'alamat'         => 'required|string',
            'kelurahan'      => 'required|string|max:50',
            'kecamatan'      => 'required|string|max:50',
            'kabupaten'      => 'required|string|max:50',
            'provinsi'       => 'required|string|max:50',
            'pendidikan'     => 'required|string|max:50',
            'nama_skema'     => 'required|string|max:150',
            'kode_skema'     => 'nullable|string|max:50',
            'tujuan_asesmen' => 'required|string|max:50',
        ]);

        $data = [
            // Meta
            'no_registrasi'    => 'REG-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'tanggal'          => now()->translatedFormat('d F Y'),

            // Bagian 1 - Data Pribadi
            'nama'             => strtoupper($request->nama),
            'nik'              => $request->nik,
            'tempat_lahir'     => $request->tempat_lahir,
            'tanggal_lahir'    => \Carbon\Carbon::parse($request->tanggal_lahir)->translatedFormat('d F Y'),
            'jenis_kelamin'    => $request->jenis_kelamin,
            'agama'            => $request->agama,
            'kebangsaan'       => $request->kebangsaan ?? 'Indonesia',
            'no_telp'          => $request->no_telp,
            'email'            => $request->email,
            'alamat'           => $request->alamat,
            'rt_rw'            => $request->rt_rw,
            'kelurahan'        => $request->kelurahan,
            'kecamatan'        => $request->kecamatan,
            'kabupaten'        => $request->kabupaten,
            'provinsi'         => $request->provinsi,
            'kode_pos'         => $request->kode_pos,
            'pendidikan'       => $request->pendidikan,

            // Bagian 1 - Data Pekerjaan
            'pekerjaan'        => $request->pekerjaan,
            'instansi'         => $request->instansi,
            'alamat_instansi'  => $request->alamat_instansi,

            // Bagian 2 - Skema Sertifikasi
            'nama_skema'       => $request->nama_skema,
            'kode_skema'       => $request->kode_skema,
            'tujuan_asesmen'   => $request->tujuan_asesmen,

            'unit_kompetensi' => [
                ['kode' => 'M.741000.004.01', 'judul' => 'Melakukan survey sumber bahan baku'],
                ['kode' => 'M.741000.006.01', 'judul' => 'Menentukan jenis produk'],                
            ],

            // Bagian 3 - Persyaratan (opsional, blade punya default)
            'persyaratan_dasar'  => $request->persyaratan_dasar ?? [],
            'bukti_administratif'=> $request->bukti_administratif ?? [],
        ];

        $pdf = Pdf::loadView('forms.apl01', $data)
                  ->setPaper('a4', 'portrait');

        $filename = 'APL-01_' . str_replace(' ', '_', $request->nama) . '_' . date('Ymd') . '.pdf';

        // return $pdf->download($filename);
        return $pdf->stream($filename); // tampil di browser
    }
}