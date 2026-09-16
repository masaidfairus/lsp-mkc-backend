<?php

// namespace App\Http\Controllers\PDF;

// use App\Http\Controllers\Controller;
// use App\Models\Apl01;
// use Barryvdh\DomPDF\Facade\Pdf;
// use Illuminate\Http\Request;

// class PdfPreviewController extends Controller
// {
//     public function apl01(Apl01 $apl01)
//     {
//         $data = [
//             'nama' => $apl01->nama,
//             'nik' => $apl01->nik,
//             'tempat_lahir' => $apl01->tempat_lahir,
//             'tanggal_lahir' => $apl01->tanggal_lahir->format('d/m/Y'),
//             'jenis_kelamin' => $apl01->jenis_kelamin,
//             'kebangsaan' => $apl01->kebangsaan,
//             'alamat' => $apl01->alamat,
//             'rt_rw' => $apl01->rt_rw,
//             'kelurahan' => $apl01->kelurahan,
//             'kecamatan' => $apl01->kecamatan,
//             'kabupaten' => $apl01->kabupaten,
//             'provinsi' => $apl01->provinsi,
//             'kode_pos' => $apl01->kode_pos,
//             'no_telp' => $apl01->no_telp,
//             'email' => $apl01->email,
//             'pendidikan' => $apl01->pendidikan,
//             'instansi' => $apl01->instansi,
//             'pekerjaan' => $apl01->pekerjaan,
//             'alamat_instansi' => $apl01->alamat_instansi,
//             'nama_skema' => $apl01->nama_skema,
//             'kode_skema' => $apl01->kode_skema,
//             'tujuan_asesmen' => $apl01->tujuan_asesmen,
//             'unit_kompetensi' => $apl01->unit_kompetensi ?? [],
//             'persyaratan_dasar' => $apl01->persyaratan_dasar ?? [],
//             'bukti_administratif' => $apl01->bukti_administratif ?? [],
//             'status_terima' => $apl01->status_terima,
//         ];

//                 // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.APL.apl01', compact($data))
//             ->setPaper('a4', 'portrait');

//         return $pdf->stream('apl01_preview.pdf');
//     }

//     public function apl01Download(Apl01 $apl01)
//     {
//         // data sama, tapi download bukan stream
//         $data = [
//             'nama' => $apl01->nama,
//             'nik' => $apl01->nik,
//             'tempat_lahir' => $apl01->tempat_lahir,
//             'tanggal_lahir' => $apl01->tanggal_lahir->format('d/m/Y'),
//             'jenis_kelamin' => $apl01->jenis_kelamin,
//             'kebangsaan' => $apl01->kebangsaan,
//             'alamat' => $apl01->alamat,
//             'rt_rw' => $apl01->rt_rw,
//             'kelurahan' => $apl01->kelurahan,
//             'kecamatan' => $apl01->kecamatan,
//             'kabupaten' => $apl01->kabupaten,
//             'provinsi' => $apl01->provinsi,
//             'kode_pos' => $apl01->kode_pos,
//             'no_telp' => $apl01->no_telp,
//             'email' => $apl01->email,
//             'pendidikan' => $apl01->pendidikan,
//             'instansi' => $apl01->instansi,
//             'pekerjaan' => $apl01->pekerjaan,
//             'alamat_instansi' => $apl01->alamat_instansi,
//             'nama_skema' => $apl01->nama_skema,
//             'kode_skema' => $apl01->kode_skema,
//             'tujuan_asesmen' => $apl01->tujuan_asesmen,
//             'unit_kompetensi' => $apl01->unit_kompetensi ?? [],
//             'persyaratan_dasar' => $apl01->persyaratan_dasar ?? [],
//             'bukti_administratif' => $apl01->bukti_administratif ?? [],
//             'status_terima' => $apl01->status_terima,
//         ];

//                 // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------

        $pdf = Pdf::loadView('forms.APL.apl01', $data)
//             ->setPaper('a4', 'portrait');

//         return $pdf->download('APL01-' . $apl01->nama . '.pdf');
//     }
// }
