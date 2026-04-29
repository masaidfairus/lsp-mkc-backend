{{-- resources/views/forms/IA/ia04a.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.04A - Daftar Instruksi Terstruktur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
            padding: 4rem;
        }

        .page {
            width: 100%;
        }

                .page-break {
            page-break-before: always;
        }

        /* Judul */
        .form-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* Header table */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .note-small {
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        /* Panduan */
        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 14px;
        }
        .panduan-box .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .panduan-box ul {
            padding-left: 16px;
        }
        .panduan-box ul li {
            margin-bottom: 4px;
            font-size: 9pt;
            text-align: justify;
        }

        /* Kelompok section */
        .kelompok-section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        /* Unit table */
        .unit-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
        }
        .unit-table th, .unit-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .unit-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }

        /* Instruksi table */
        .instruksi-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
        }
        .instruksi-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            word-wrap: break-word;
        }
        .instruksi-table .label-col {
            width: 32%;
        }
        .instruksi-table .value-col {
            width: 68%;
        }

        .catatan-footer {
            font-size: 8.5pt;
            margin-top: 4px;
            margin-bottom: 14px;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 2rem;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            word-wrap: break-word;
            height: 100px;
        }

        /* Penyusun & Validator */
        .pv-title {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .pv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            text-align: center;
            word-wrap: break-word;
        }
        .pv-table th {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .pv-table .status-cell {
            font-weight: bold;
        }
        .pv-table .ttd-cell {
            height: 40px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .header-table .val {
            font-weight: bold;
        }

        .note-small {
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        /* Panduan */
        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 14px;
        }
        .panduan-box .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .panduan-box ul {
            padding-left: 16px;
        }
        .panduan-box ul li {
            margin-bottom: 4px;
            font-size: 9pt;
            text-align: justify;
        }
        .panduan-box .sub-text {
            padding-left: 16px;
            font-size: 9pt;
            text-align: justify;
            margin-bottom: 4px;
        }

        /* Kelompok */
        .kelompok-section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        /* Unit table */
        .unit-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .unit-table th, .unit-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .unit-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }

        /* Pertanyaan table */
        .pertanyaan-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .pertanyaan-table th, .pertanyaan-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
            word-wrap: break-word;
        }
        .pertanyaan-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }
        .col-no         { width: 5%;  text-align: center; }
        .col-pertanyaan { width: 75%; }
        .col-ya         { width: 10%; text-align: center; }
        .col-tdk        { width: 10%; text-align: center; }

        .tanggapan-label { font-weight: bold; }
        .tanggapan-area  { min-height: 28px; }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
        }

        /* Umpan balik */
        .umpan-balik-box {
            border: 1px solid #000;
            min-height: 70px;
            padding: 6px;
            margin-bottom: 12px;
            font-size: 9.5pt;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 16px;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
            word-wrap: break-word;
        }
        .ttd-table .section-header td {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .ttd-table .ttd-row td {
            height: 45px;
        }

        .footer-note {
            font-size: 8pt;
            color: #444;
            margin-top: 8px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

               .judul { font-size: 10pt; font-weight: bold; margin-bottom: 8px; }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 9pt;
            line-height: 1.4;
        }

        .tbl-kp {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .tbl-kp th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-kp td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin: 10px 0 6px 0;
        }

        .petunjuk-list {
            padding-left: 20px;
            margin-bottom: 12px;
        }
        .petunjuk-list li {
            margin-bottom: 3px;
            font-size: 9pt;
            line-height: 1.4;
        }

        .field-line {
            font-size: 9pt;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .field-space {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #000;
            margin-left: 6px;
            vertical-align: bottom;
        }

        .perlengkapan-list {
            padding-left: 22px;
            margin: 3px 0 8px 0;
        }
        .perlengkapan-list li {
            font-size: 9pt;
            margin-bottom: 2px;
        }

        /* Footer TTD */
        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
            margin-bottom: 14px;
        }
        .tbl-ttd td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }
        .tbl-ttd .header-cell {
            font-weight: bold;
            background: #f0f0f0;
        }
        .tbl-ttd .ttd-space { height: 35px; }

        /* Penyusun validator */
        .tbl-pv {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
        }
        .tbl-pv th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-pv td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            text-align: center;
        }
        .tbl-pv td.ttd-space { height: 40px; }
        .pv-table .ttd-cell {
            height: 40px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .header-table .val {
            font-weight: bold;
        }

        .note-small {
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        .sig-block {
            text-align: left;
        }
        .sig-image {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
@php
    $judul = $judul ?? '';
    $nomor = $nomor ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">
        FR.IA.04A. DIT – DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK<br>
        SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)
    </div>

    {{-- HEADER --}}
    <table class="tbl" style="page-break-inside: avoid;">
        <colgroup>
            <col style="width:28%;">
            <col style="width:14%;">
            <col style="width:3%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td>Judul :</td>
            <td style="font-weight:bold;" colspan="5">{{ $judul }}</td>
        </tr>
        <tr>
            <td>Nomor :</td>
            <td style="font-weight:bold;" colspan="5">{{ $nomor }}</td>
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

    {{-- PANDUAN --}}
    <div class="panduan-box" style="page-break-inside: avoid;">
        <div class="panduan-title">PANDUAN BAGI ASESOR</div>
        <ul>
            <li>Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</li>
            <li>Proyek singkat atau kegiatan terstruktur lainnya dibuat untuk keseluruhan unit kompetensi dalam Skema Sertifikasi atau untuk masing-masing kelompok pekerjaan.</li>
            <li>Kumpulkan hasil proyek singkat atau kegiatan terstruktur lainnya sesuai dengan hasil keluaran yang telah ditetapkan.</li>
        </ul>
    </div>

    {{-- KELOMPOK PEKERJAAN --}}
    @foreach ($kelompok as $kp)
    <div class="kelompok-section">

        {{-- Unit Table --}}
        <table class="unit-table">
            <thead>
                <tr>
                    <th style="width:22%">Kelompok<br>Pekerjaan {{ $kp['no'] }}</th>
                    <th style="width:6%">No.</th>
                    <th style="width:22%">Kode Unit</th>
                    <th>Judul Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kp['units'] as $i => $unit)
                <tr>
                    @if ($i === 0)
                    <td rowspan="{{ count($kp['units']) }}" style="vertical-align:middle">
                        {{ $kp['nama'] }}
                    </td>
                    @endif
                    <td style="text-align:center">{{ !empty($unit['no']) ? $unit['no'] : ($i + 1) }}.</td>
                    <td>{{ $unit['kode'] }}</td>
                    <td>{{ $unit['judul'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Instruksi Table --}}
        <table class="instruksi-table">
            <tr>
                <td class="label-col">
                    Hal yang harus disiapkan atau dilakukan atau dihasilkan untuk suatu proyek singkat/ kegiatan terstruktur
                </td>
                <td class="value-col">
                    Skenario studi kasus/kegiatan terstruktur yang berisikan data informasi,
                    lingkup bahasan dan instruksi untuk asesi:<br><br>
                    {{ $kp['skenario'] }}
                    <br><br><br>
                    Waktu : {{ $kp['waktu_skenario'] }}
                </td>
            </tr>
            <tr>
                <td class="label-col">Hal yang perlu didemonstrasikan</td>
                <td class="value-col">
                    Hasil studi kasus atau kegiatan terstruktur:<br><br>
                    {{ $kp['hasil'] }}
                    <br><br><br>
                    Waktu : {{ $kp['waktu_hasil'] }}
                </td>
            </tr>
            <tr class="umpan-row">
                <td colspan="2">
                    Umpan balik untuk Asesi:<br><br>
                    {{ $kp['umpan_balik'] }}
                </td>
            </tr>
        </table>

        <div class="catatan-footer">
            *) Apabila asesi pada Level 4 ke atas, berikan tugas proyek yang meliputi tentang pemecahan masalah dan analisa
        </div>

    </div>
    @endforeach

        <div class="page-break"></div>
    {{-- TANDA TANGAN --}}
    <table class="ttd-table" style="page-break-inside: avoid;">
        <tr>
            <td style="width:33.33%;">
                Tanda Tangan Asesi<br><br>
                <div class="sig-block">
                    @if($asesi_signature ?? null)
                        <img src="{{ $asesi_signature }}" class="sig-image" style="max-height: 70px;">
                    @endif
                    <div>{{ $asesi_nama ?? '' }}</div>
                    <div>{{ $tanggal }}</div>
                </div>
            </td>
            <td style="width:33.33%;">
                Tanda Tangan Asesor<br><br>
                <div class="sig-block">
                    @if($asesor_qr_code ?? null)
                        <img src="{{ $asesor_qr_code }}" class="sig-image" style="width: 70px; height: 70px;">
                    @elseif($asesor_signature ?? null)
                        <img src="{{ $asesor_signature }}" class="sig-image" style="max-height: 70px;">
                    @endif
                    <div>{{ $asesor_name ?? '' }}</div>
                    <div>{{ $asesor_date ?? '' }}</div>
                </div>
            </td>
            <td style="width:33.33%;">Nama &amp; Tanda Tangan<br>Supervisor (Jika ada)</td>
        </tr>
    </table>

    {{-- PENYUSUN DAN VALIDATOR --}}
    <div style="page-break-inside: avoid;">
        <div class="pv-title">PENYUSUN DAN VALIDATOR</div>
        <table class="pv-table">
            <thead>
                <tr>
                    <th style="width:15%">STATUS</th>
                    <th style="width:8%">NO</th>
                    <th style="width:35%">NAMA</th>
                    <th style="width:20%">NOMOR MET</th>
                    <th style="width:22%">TANDA TANGAN<br>DAN TANGGAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penyusun as $i => $p)
                <tr>
                    @if ($i === 0)
                    <td class="status-cell" rowspan="{{ count($penyusun) }}">PENYUSUN</td>
                    @endif
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
                    <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
                    <td class="ttd-cell">
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
                    <td class="status-cell" rowspan="{{ count($validator) }}">VALIDATOR</td>
                    @endif
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
                    <td>{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
                    <td class="ttd-cell">
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

</div>
</body>
</html>