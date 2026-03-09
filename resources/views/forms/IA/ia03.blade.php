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

        /* Header table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
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
            margin-bottom: 8px;
        }
        .unit-table th, .unit-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
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
        }
        .pertanyaan-table th, .pertanyaan-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
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
            margin-bottom: 16px;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
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
    </style>
</head>
<body>
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI</div>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width:28%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td style="width:10%">Judul</td>
            <td style="width:3%; text-align:center">:</td>
            <td class="val">{{ $judul }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center">:</td>
            <td class="val">{{ $nomor }}</td>
        </tr>
        <tr>
            <td>TUK</td>
            <td></td>
            <td style="text-align:center">:</td>
            <td>{{ $tuk }}</td>
        </tr>
        <tr>
            <td>Nama Asesor</td>
            <td></td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesor }}</td>
        </tr>
        <tr>
            <td>Nama Asesi</td>
            <td></td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td></td>
            <td style="text-align:center">:</td>
            <td>{{ $tanggal }}</td>
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
                    <td style="text-align:center">{{ $unit['no'] }}.</td>
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
                    <td class="col-no" style="padding-top:6px">{{ $p['no'] }}.</td>
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
                        <span class="checkbox"></span>
                    </td>
                    <td style="text-align:center; vertical-align:middle">
                        <span class="checkbox"></span>
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
            <td>{{ $asesi_ttd_tanggal }}</td>
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
            <td>{{ $asesor_ttd_tanggal }}</td>
        </tr>
    </table>

    <div class="footer-note">
        Diadaptasi dari template yang disediakan di Departemen Pendidikan dan Pelatihan, Australia.
        Merancang instrumen asesmen untuk hasil yang berkualitas di VET, 2008 di VET, 2008
    </div>

</div>
</body>
</html>