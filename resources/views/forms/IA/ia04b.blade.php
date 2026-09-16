{{-- resources/views/forms/IA/ia04b.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.04B - Penilaian Proyek Singkat</title>
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
        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 1rem;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
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

        /* Aspek Penilaian table */
        .aspek-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 16px;
        }
        .aspek-table th, .aspek-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 9.5pt;
            vertical-align: top;
            word-wrap: break-word;
        }
        .aspek-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }

        .col-no        { width: 5%;  text-align: center; vertical-align: middle; }
        .col-lingkup   { width: 28%; }
        .col-pertanyaan{ width: 30%; }
        .col-kesesuaian{ width: 25%; }
        .col-ya        { width: 6%;  text-align: center; vertical-align: middle; }
        .col-tdk       { width: 6%;  text-align: center; vertical-align: middle; }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 16px;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            width: 33.33%;
            height: 100px;
            word-wrap: break-word;
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
    </style>
</head>
<body>
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">
        FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA
    </div>

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
            <li>Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.</li>
        </ul>
    </div>

    {{-- ASPEK PENILAIAN --}}
    <table class="aspek-table">
        <thead>
            <tr>
                <th colspan="12">Aspek Penilaian</th>
                <th colspan="2">Pencapaian</th>
            </tr>
            <tr>
                <th class="col-no"></th>
                <th colspan="5" class="col-lingkup">Lingkup Penyajian proyek atau<br>kegiatan terstruktur lainnya</th>
                <th colspan="3" class="col-pertanyaan">Daftar Pertanyaan</th>
                <th colspan="3" class="col-kesesuaian">Kesesuaian dengan standar<br>kompetensi kerja</th>
                <th class="col-ya">Ya</th>
                <th class="col-tdk">Tdk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aspek_penilaian as $i => $aspek)
            <tr class="row-data">
                <td class="col-no">{{ $aspek['lingkup'] === 'dst.' ? 'dst.' : ($i + 1) . '.' }}</td>
                <td colspan="5" class="col-lingkup">{{ $aspek['lingkup'] === 'dst.' ? '' : $aspek['lingkup'] }}</td>
                
                @if ($i === 0)
                <td colspan="3" class="col-pertanyaan" rowspan="{{ count($aspek_penilaian) }}">
                    {!! nl2br(e($full_pertanyaan)) !!}
                </td>
                <td colspan="3" class="col-kesesuaian" rowspan="{{ count($aspek_penilaian) }}">
                    {!! nl2br(e($full_kesesuaian)) !!}
                </td>
                @endif

                <td class="col-ya"><span style="font-size: 13pt;">{!! ($aspek['achievement'] ?? '') === 'yes' ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span></td>
                <td class="col-tdk"><span style="font-size: 13pt;">{!! ($aspek['achievement'] ?? '') === 'no' ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- REKOMENDASI --}}
    <table class="header-table" style="margin-bottom: 16px;">
        <tr>
            <td style="width:28%; font-weight:bold">Rekomendasi Asesor:</td>
            <td>
                Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:<br>
                <span style="font-size: 13pt;">{!! ($rekomendasi ?? '') === 'competent' ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span> &nbsp;Kompeten<br>
                <span style="font-size: 13pt;">{!! ($rekomendasi ?? '') === 'not_yet_competent' ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span> &nbsp;Belum Kompeten
            </td>
        </tr>
    </table>

            <div class="page-break"></div>
    {{-- TANDA TANGAN --}}
    <table class="ttd-table">
        <tr>
            <td style="vertical-align:top">
                Asesi :<br>
                Nama : {{ $nama_asesi ?? '' }}<br><br>
                Tanda tangan/Tanggal :<br>
                @if($asesi_signature ?? null)
                    <img src="{{ $asesi_signature }}" style="max-height: 40px;"><br>
                @endif
                {{ $tanggal ?? '' }}
            </td>
            <td style="vertical-align:top">
                Asesor :<br>
                Nama : {{ $nama_asesor ?? '' }}<br><br> <br>
                Tanda tangan/Tanggal :<br>
                @if($asesor_qr_code ?? null)
                    <img src="{{ $asesor_qr_code }}" style="width: 45px; height: 45px;"><br>
                @elseif($asesor_signature ?? null)
                    <img src="{{ $asesor_signature }}" style="max-height: 40px;"><br>
                @endif
                {{ $tanggal ?? '' }}
            </td>
            <td style="vertical-align:top">Nama &amp; Tanda Tangan<br>Supervisor (Jika ada)</td>
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
                <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
                <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
                <td class="ttd-cell">
                    @if($p['qr_code'] ?? null)
                        <img src="{{ $p['qr_code'] }}" style="width: 45px; height: 45px; vertical-align: middle;"><br>
                    @elseif($p['signature'] ?? null)
                        <img src="{{ $p['signature'] }}" style="max-height: 35px; vertical-align: middle;"><br>
                    @endif
                    {{ $p['date'] ?? '' }}
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
                    @if($v['qr_code'] ?? null)
                        <img src="{{ $v['qr_code'] }}" style="width: 45px; height: 45px; vertical-align: middle;"><br>
                    @elseif($v['signature'] ?? null)
                        <img src="{{ $v['signature'] }}" style="max-height: 35px; vertical-align: middle;"><br>
                    @endif
                    {{ $v['date'] ?? '' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</body>
</html>
