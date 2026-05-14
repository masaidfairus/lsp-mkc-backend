{{-- resources/views/forms/IA/ia08.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.08 - Ceklis Verifikasi Portofolio</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
            line-height: 1.35;
        }
        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }
        .judul {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 12px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .tbl th {
            border: 1px solid #000;
            padding: 5px 7px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            word-wrap: break-word;
        }
        .header-val { font-weight: bold; }

        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 15px;
            word-wrap: break-word;
        }
        .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .portofolio-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .portofolio-table th, .portofolio-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            text-align: center;
            word-wrap: break-word;
        }
        .portofolio-table .bukti-col { width: 35%; text-align: left; }

        .klarifikasi-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .klarifikasi-table th, .klarifikasi-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .klarifikasi-table th { background: #e0e0e0; font-weight: bold; text-align: center; }

        .rekomen-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 15px;
            word-wrap: break-word;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .ttd-header {
            font-weight: bold;
            background: #e8e8e8;
        }
        .ttd-space { height: 65px; }

        .pv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: avoid;
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            text-align: center;
            word-wrap: break-word;
            vertical-align: middle;
        }
        .pv-table th {
            background: #e0e0e0;
            font-weight: bold;
        }
        .sig-block {
            text-align: left;
        }
        .sig-image {
            display: block;
            margin-bottom: 5px;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            position: relative;
            vertical-align: middle;
        }

        .cb-checked::after {
            content: '';
            position: absolute;
            left: 2px;
            top: -1px;
            width: 3px;
            height: 7px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>
</head>
<body>

@php
    $nama_skema = $nama_skema ?? '';
    $kode_skema = $kode_skema ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $bukti_portofolio = $bukti_portofolio ?? [];
    $follow_up_verification = $follow_up_verification ?? [];
    $klarifikasi_list = $klarifikasi_list ?? [];
    $umpan_balik = $umpan_balik ?? '';
    $rekomendasi_kompeten = $rekomendasi_kompeten ?? false;
    $rekomendasi_belum = $rekomendasi_belum ?? false;
    $asesi_signature = $asesi_signature ?? null;
    $asesor_signature = $asesor_signature ?? null;
    $asesor_qr_code = $asesor_qr_code ?? null;
    $asesor_reg = $asesor_reg ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="judul">FR.IA.08. CVP - CEKLIS VERIFIKASI PORTOFOLIO</div>

<table class="tbl">
    <colgroup>
        <col style="width:28%;">
        <col style="width:14%;">
        <col style="width:3%;">
        <col style="width:55%;">
    </colgroup>
    <tr>
        <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
        <td>Judul :</td>
        <td style="font-weight:bold;" colspan="5">{{ $nama_skema }}</td>
    </tr>
    <tr>
        <td>Nomor :</td>
        <td style="font-weight:bold;" colspan="5">{{ $kode_skema }}</td>
    </tr>
    <tr>
        <td colspan="3">TUK :</td>
        <td colspan="5">{{ $tuk }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesor :</td>
        <td colspan="5">{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesi :</td>
        <td colspan="5">{{ $nama_asesi }}</td>
    </tr>
    <tr>
        <td colspan="3">Tanggal :</td>
        <td colspan="5">{{ $tanggal }}</td>
    </tr>
</table>

<div class="panduan-box" style="padding: 10px;">
    <div class="panduan-title">PANDUAN BAGI ASESOR</div>
    <ul style="padding-left:15px; font-size: 8.5pt;">
        <li>Verifikasi portofolio dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dilakukan untuk masing-masing kelompok pekerjaan dalam satu skema sertifikasi.</li>
        <li>Isilah bukti portofolio sesuai ketentuan bukti berkualitas dan relevan dengan standar kompetensi kerja sebagaimana yang telah disepakati pada rekaman asesmen mandiri.</li>
        <li>Lakukan verifikasi portofolio berdasarkan aturan bukti.</li>
        <li>Berikan hasil verifikasi portofolio dengan memberi centang (V) pada kolom yang sesuai.</li>
        <li>Jika hasil verifikasi dokumen portofolio belum memenuhi aturan bukti maka asesor melanjutkan dengan metode tanya jawab pertanyaan wawancara dan/atau verifikasi pihak ketiga.</li>
    </ul>
</div>

<table class="portofolio-table">
    <thead>
        <tr>
            <th rowspan="3" style="width:40px">1.</th>
            <th colspan="6" rowspan="3">Bukti Portofolio:</th>
            <th colspan="8">Aturan Bukti</th>
        </tr>
        <tr>
            <th colspan="2">Valid</th>
            <th colspan="2">Asli</th>
            <th colspan="2">Terkini</th>
            <th colspan="2">Memadai</th>
        </tr>
        <tr>
            <th style="width:30px; font-size: 10px;">Ya</th>
            <th style="width:30px; font-size: 10px;">Tidak</th>
            <th style="width:30px; font-size: 10px;">Ya</th>
            <th style="width:30px; font-size: 10px;">Tidak</th>
            <th style="width:30px; font-size: 10px;">Ya</th>
            <th style="width:30px; font-size: 10px;">Tidak</th>
            <th style="width:30px; font-size: 10px;">Ya</th>
            <th style="width:30px; font-size: 10px;">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @if(count($bukti_portofolio) > 0)
            @foreach ($bukti_portofolio as $i => $b)
            <tr style="page-break-inside: avoid;">
                <td>{{ $i + 1 }}.</td>
                <td colspan="6" class="bukti-col" style="text-align: left;">{{ $b['nama_bukti'] }}</td>
                <td><span class="{!! $b['v'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! !$b['v'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! $b['a'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! !$b['a'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! $b['t'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! !$b['t'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! $b['m'] ? 'cb-checked' : 'cb' !!}"></span></td>
                <td><span class="{!! !$b['m'] ? 'cb-checked' : 'cb' !!}"></span></td>
            </tr>
            @endforeach
        @else
            @for($i=1; $i<=4; $i++)
            <tr>
                <td>{{ $i }}.</td>
                <td style="height: 25px;"></td>
                <td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td><td><span class="cb"></span></td>
            </tr>
            @endfor
        @endif
    </tbody>
</table>

<div style="font-weight:bold; margin-bottom:5px; font-size: 9pt;">
    Sebagai tindak lanjut dari hasil verifikasi bukti, substansi materi di bawah ini (no elemen yg di cek list) harus diklarifikasi selama wawancara:
</div>
<table class="klarifikasi-table">
    <thead>
        <tr>
            <th style="width:40px">Cek List</th>
            <th style="width:120px">No. Unit Kompetensi</th>
            <th style="width:100px">No. Elemen</th>
            <th>Materi/substansi wawancara (elemen/KUK)</th>
        </tr>
    </thead>
    <tbody>
        @if(count($follow_up_verification) > 0)
            @foreach($follow_up_verification as $i => $f)
            <tr style="page-break-inside: avoid;">
                <td style="text-align:center;"><span class="{!! ($f['is_checked'] ?? false) ? 'cb-checked' : 'cb' !!}"></span></td>
                <td style="text-align:center;">{{ $f['unit_code'] ?? '' }}</td>
                <td style="text-align:center;">{{ $f['element_no'] ?? '' }}</td>
                <td>{{ $f['interview_substance'] ?? '' }}</td>
            </tr>
            @endforeach
        @else
            @for($i=1; $i<=5; $i++)
            <tr>
                <td style="text-align:center;"><span class="cb"></span></td>
                <td style="height: 25px;"></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        @endif
    </tbody>
</table>

<div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
    <strong>Bukti tambahan diperlukan pada unit / elemen kompetensi sebagai berikut:</strong><br><br>
    {{ $umpan_balik ?: '...' }}
</div>

<table class="ttd-table" style="margin-bottom: 20px;">
    <tr>
        <td style="width: 30%; font-weight: bold; background: #f0f0f0;">Rekomendasi Asesor:</td>
        <td style="padding: 10px;">
            @if($rekomendasi_kompeten)
                <div style="display: flex; align-items: flex-start;">
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14pt;">&#9745;</span>
                    <div style="margin-left: 10px; display: inline-block; vertical-align: top;">
                        Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>KOMPETEN</strong>
                    </div>
                </div>
            @elseif($rekomendasi_belum)
                <div style="display: flex; align-items: flex-start;">
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14pt;">&#9745;</span>
                    <div style="margin-left: 10px; display: inline-block; vertical-align: top;">
                        Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>OBSERVASI LANGSUNG</strong> pada:<br>
                        @if(isset($recommendation['observation_details']))
                            Kelompok Pekerjaan: {{ $recommendation['observation_details']['work_group'] ?? '...' }}<br>
                            Unit: {{ $recommendation['observation_details']['unit'] ?? '...' }}<br>
                            Elemen: {{ $recommendation['observation_details']['element'] ?? '...' }}<br>
                            KUK: {{ $recommendation['observation_details']['kuk'] ?? '...' }}
                        @else
                            Kelompok Pekerjaan: ...<br>
                            Unit: ...<br>
                            Elemen: ...<br>
                            KUK: ...
                        @endif
                    </div>
                </div>
            @else
                <div style="margin-bottom: 15px; display: flex; align-items: flex-start;">
                    <span class="cb"></span>
                    <div style="margin-left: 10px; display: inline-block; vertical-align: top;">
                        Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>KOMPETEN</strong>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start;">
                    <span style="font-family: DejaVu Sans, sans-serif; font-size: 14pt;">&#9744;</span>
                    <div style="margin-left: 10px; display: inline-block; vertical-align: top;">
                        Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>OBSERVASI LANGSUNG</strong> pada:<br>
                        Kelompok Pekerjaan: ...<br>
                        Unit: ...<br>
                        Elemen: ...<br>
                        KUK: ...
                    </div>
                </div>
            @endif
        </td>
    </tr>
</table>

<table class="ttd-table">
    <tr><td colspan="3" class="ttd-header">Asesi :</td></tr>
    <tr>
        <td style="width:25%">Nama</td>
        <td style="width:3%;text-align:center">:</td>
        <td>{{ $nama_asesi }}</td>
    </tr>
    <tr style="height:70px">
        <td>Tanda tangan & Tanggal</td>
        <td style="text-align:center">:</td>
        <td>
            <div class="sig-block">
                @if($asesi_signature)
                    <img src="{{ $asesi_signature }}" class="sig-image" style="max-height:65px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
        </td>
    </tr>
    <tr><td colspan="3" class="ttd-header">Asesor :</td></tr>
    <tr>
        <td>Nama</td>
        <td style="text-align:center">:</td>
        <td>{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td>No. Reg</td>
        <td style="text-align:center">:</td>
        <td>{{ $asesor_reg }}</td>
    </tr>
    <tr style="height:70px">
        <td>Tanda tangan & Tanggal</td>
        <td style="text-align:center">:</td>
        <td>
            <div class="sig-block">
                @if($asesor_qr_code)
                    <img src="{{ $asesor_qr_code }}" class="sig-image" style="width:50px;height:50px;">
                @elseif($asesor_signature)
                    <img src="{{ $asesor_signature }}" class="sig-image" style="max-height:65px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
        </td>
    </tr>
</table>

<div style="page-break-inside: avoid;">
<div style="font-weight:bold; margin-bottom:5px;">PENYUSUN DAN VALIDATOR</div>
<table class="pv-table">
    <thead>
        <tr>
            <th style="width:15%">STATUS</th>
            <th style="width:8%">NO</th>
            <th style="width:35%">NAMA</th>
            <th style="width:20%">NOMOR MET</th>
            <th style="width:22%">TANDA TANGAN DAN TANGGAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($penyusun as $i => $p)
        <tr>
            @if ($i === 0)
            <td rowspan="{{ count($penyusun) }}" style="font-weight:bold;">PENYUSUN</td>
            @endif
            <td>{{ $i + 1 }}</td>
            <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
            <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
            <td>
                <div class="sig-block" style="text-align: center;">
                    @if($p['qr_code'] ?? null)
                        <img src="{{ $p['qr_code'] }}" class="sig-image" style="width: 45px; height: 45px; margin: 0 auto 5px auto;">
                    @elseif($p['signature'] ?? null)
                        <img src="{{ $p['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                    @endif
                    <div>{{ $p['date'] ?? '' }}</div>
                </div>
            </td>
        </tr>
        @endforeach
        @foreach ($validator as $i => $v)
        <tr>
            @if ($i === 0)
            <td rowspan="{{ count($validator) }}" style="font-weight:bold;">VALIDATOR</td>
            @endif
            <td>{{ $i + 1 }}</td>
            <td>{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
            <td>{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
            <td>
                <div class="sig-block" style="text-align: center;">
                    @if($v['qr_code'] ?? null)
                        <img src="{{ $v['qr_code'] }}" class="sig-image" style="width: 45px; height: 45px; margin: 0 auto 5px auto;">
                    @elseif($v['signature'] ?? null)
                        <img src="{{ $v['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                    @endif
                    <div>{{ $v['date'] ?? '' }}</div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

</body>
</html>
