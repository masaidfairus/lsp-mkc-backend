{{-- resources/views/forms/IA/ia03.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.03 - Pertanyaan Untuk Mendukung Observasi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
            padding: 4rem
        }

        .page {
            width: 100%;
        }

        .form-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 8px;
        }

       5b

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
    </style>
</head>
<body>

@php
    $nama_skema         = $nama_skema ?? '';
    $kode_skema         = $kode_skema ?? '';
    $tuk                = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
    $nama_asesor        = $nama_asesor ?? '';
    $nama_asesi         = $nama_asesi ?? '';
    $tanggal            = $tanggal ?? '';
    $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
    $penyusun           = $penyusun ?? [['nama'=>'','nomor_met'=>''],['nama'=>'','nomor_met'=>'']];
    $validator          = $validator ?? [['nama'=>'','nomor_met'=>''],['nama'=>'','nomor_met'=>'']];
@endphp

<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI</div>

    {{-- HEADER --}}
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
    <div class="panduan-box">
        <div class="panduan-title">PANDUAN BAGI ASESOR</div>
        <ul>
            <li>Formulir ini di isi oleh asesor kompetensi dapat sebelum, pada saat atau setelah melakukan asesmen dengan metode observasi demonstrasi.</li>
            <li>Pertanyaan dibuat dengan tujuan untuk menggali, dapat berisi pertanyaan yang berkaitan dengan dimensi kompetensi, batasan variabel dan aspek kritis yang relevan dengan skenario tugas dan praktik demonstrasi.</li>
            <li>Jika pertanyaan disampaikan sebelum asesi melakukan praktik demonstrasi, maka pertanyaan dibuat berkaitan dengan aspek K3L, SOP, penggunaan peralatan dan perlengkapan.</li>
            <li>Jika setelah asesi melakukan praktik demonstrasi terdapat item pertanyaan pendukung observasi telah terpenuhi, maka pertanyaan tersebut tidak perlu ditanyakan lagi dan cukup memberi catatan bahwa sudah terpenuhi pada saat tugas praktek demonstrasi pada kolom tanggapan</li>
            <li>Jika pada saat observasi ada hal yang perlu dikonfirmasi sedangkan di instrumen daftar pertanyaan pendukung observasi tidak ada, maka asesor dapat memberikan pertanyaan</li>
        </ul>
        <div class="sub-text">
            dengan syarat pertanyaan harus berkaitan dengan tugas praktek demonstrasi. Jika dilakukan, asesor harus mencatat dalam instrumen pertanyaan pendukung observasi.
        </div>
        <ul>
            <li>Tanggapan asesi ditulis pada kolom tanggapan.</li>
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

        {{-- Pertanyaan Table --}}
        <table class="pertanyaan-table">
            <thead>
                <tr>
                    <th class="col-no" rowspan="2"></th>
                    <th class="col-pertanyaan" rowspan="2">Pertanyaan</th>
                    <th colspan="2">Pencapaian</th>
                </tr>
                <tr>
                    <th class="col-ya">Ya</th>
                    <th class="col-tdk">Tdk</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kp['pertanyaan'] as $p)
                <tr>
                    <td class="col-no" style="padding-top:6px">{{ !empty($p['no']) ? $p['no'] : ($loop->iteration) }}.</td>
                    <td class="col-pertanyaan" style="padding:6px">{{ $p['pertanyaan'] }}</td>
                    <td class="col-ya"></td>
                    <td class="col-tdk"></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:4px 6px">
                        <span class="tanggapan-label">Tanggapan:</span>
                        <div class="tanggapan-area">{{ $p['tanggapan'] ?? '' }}</div>
                    </td>
                    <td style="text-align:center; vertical-align:middle">
                        <span style="font-size: 13pt;">{!! ($p['is_yes'] ?? false) ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span>
                    </td>
                    <td style="text-align:center; vertical-align:middle">
                        <span style="font-size: 13pt;">{!! ($p['is_no'] ?? false) ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @endforeach

    {{-- UMPAN BALIK --}}
    <div class="umpan-balik-box">
        <strong>Umpan balik untuk asesi:</strong><br>
        {{ $umpan_balik }}
    </div>

    {{-- TANDA TANGAN --}}
    <table class="ttd-table">
        <tr class="section-header">
            <td colspan="3"><strong>Asesi :</strong></td>
        </tr>
        <tr>
            <td style="width:28%">Nama</td>
            <td style="width:3%; text-align:center">:</td>
            <td>{{ $asesi_nama }}</td>
        </tr>
        <tr class="ttd-row">
            <td>Tanda tangan<br>dan Tanggal</td>
            <td style="text-align:center">:</td>
            <td>
                @if($asesi_signature ?? null)
                    <img src="{{ $asesi_signature }}" style="max-height: 80px;"><br>
                @endif
                {{ $asesi_ttd_tanggal }}
            </td>
        </tr>
        <tr class="section-header">
            <td colspan="3"><strong>Asesor :</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="text-align:center">:</td>
            <td>{{ $asesor_nama }}</td>
        </tr>
        <tr>
            <td>No. Reg</td>
            <td style="text-align:center">:</td>
            <td>{{ $asesor_no_reg }}</td>
        </tr>
        <tr class="ttd-row">
            <td>Tanda tangan<br>dan Tanggal</td>
            <td style="text-align:center">:</td>
            <td>
                @if($asesor_qr_code ?? null)
                    <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px;"><br>
                @elseif($asesor_signature ?? null)
                    <img src="{{ $asesor_signature }}" style="max-height: 80px;"><br>
                @endif
                {{ $asesor_ttd_tanggal }}
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Diadaptasi dari template yang disediakan di Departemen Pendidikan dan Pelatihan, Australia.
        Merancang instrumen asesmen untuk hasil yang berkualitas di VET, 2008 di VET, 2008
    </div>

</div>
</body>
</html>
