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

        /* Judul */
        .form-title {
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* Header table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .header-table .val { font-weight: bold; }

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
            margin-bottom: 0;
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

        /* Instruksi table */
        .instruksi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .instruksi-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .instruksi-table .label-col {
            width: 32%;
            vertical-align: top;
        }
        .instruksi-table .value-col {
            width: 68%;
        }
        .instruksi-table .waktu-row {
            padding-top: 30px;
        }
        .instruksi-table .umpan-row td {
            min-height: 50px;
            height: 50px;
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
            margin-bottom: 2rem;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            width: 33.33%;
            min-height: 60px;
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
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            text-align: center;
        }
        .pv-table th {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .pv-table .status-cell {
            font-weight: bold;
        }
        .pv-table .ttd-cell {
            min-height: 40px;
            height: 40px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">
        FR.IA.04A. DIT – DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK<br>
        SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)
    </div>

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
            <td colspan="2">TUK</td>
            <td style="text-align:center">:</td>
            <td>{{ $tuk }}</td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesor</td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesor }}</td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesi</td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td colspan="2">Tanggal</td>
            <td style="text-align:center">:</td>
            <td>{{ $tanggal }}</td>
        </tr>
    </table>
    {{-- PANDUAN --}}
    <div class="panduan-box">
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
                    <td style="text-align:center">{{ $unit['no'] }}.</td>
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

    {{-- TANDA TANGAN --}}
<table class="ttd-table">
    <tr>
        <td style="width:33.33%; height:140px; vertical-align:top">Tanda Tangan Asesi</td>
        <td style="width:33.33%; height:140px; vertical-align:top">Tanda Tangan Asesor</td>
        <td style="width:33.33%; height:140px; vertical-align:top">Nama &amp; Tanda Tangan<br>Supervisor (Jika ada)</td>
    </tr>
</table>

    {{-- PENYUSUN DAN VALIDATOR --}}
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
                <td>{{ $p['nama'] }}</td>
                <td>{{ $p['nomor_met'] }}</td>
                <td class="ttd-cell"></td>
            </tr>
            @endforeach
            @foreach ($validator as $i => $v)
            <tr>
                @if ($i === 0)
                <td class="status-cell" rowspan="{{ count($validator) }}">VALIDATOR</td>
                @endif
                <td>{{ $i + 1 }}</td>
                <td>{{ $v['nama'] }}</td>
                <td>{{ $v['nomor_met'] }}</td>
                <td class="ttd-cell"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</body>
</html>