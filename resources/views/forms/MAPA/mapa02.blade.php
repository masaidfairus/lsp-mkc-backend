<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.MAPA.02</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
        }
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
            size: A4 portrait;
        }

        .page-break {
            page-break-before: always;
        }

        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 0.3px;
        }
        .tbl-skema {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .tbl-skema td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
        }
        .tbl-unit {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        .tbl-unit th {
            border: 1px solid #000;
            padding: 5px 6px;
            background: #ddd;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tbl-unit td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .tbl-unit th { border: 1px solid #000; padding: 5px; background: #ddd; font-weight: bold; }
        .tbl-unit td { border: 1px solid #000; padding: 5px 7px; }

        /* Tabel instrumen asesmen */
        .tbl-instrumen {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
            margin-bottom: 6px;
        }

        .tbl-instrumen th {
            border: 1px solid #000;
            padding: 5px 5px;
            background: #ececec;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tbl-instrumen td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .tbl-instrumen td.center {
            text-align: center;
        }

        .tbl-instrumen td.no-col {
            text-align: center;
            white-space: nowrap;
            padding: 5px 3px;
        }

        .tbl-instrumen th.no-col {
            white-space: nowrap;
            padding: 5px 3px;
        }

        .tbl-instrumen th.cb-col {
            padding: 5px 2px;
        }

        .tbl-instrumen td.cb-col {
            text-align: center;
            padding: 5px 2px;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 4px;
            position: relative;
        }

        .cb-checked::after {
            display: inline-block;
            content: '';
            position: absolute;
            left: 3px;
            top: 0px;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .catatan {
            font-size: 7.5pt;
            font-style: italic;
            color: #333;
            margin-bottom: 14px;
        }

        /* Keterangan potensi asesi */
        .keterangan {
            font-size: 8pt;
            margin-bottom: 14px;
        }

        .keterangan ol {
            padding-left: 16px;
            margin-top: 3px;
        }

        .keterangan li {
            margin-bottom: 2px;
            line-height: 1.4;
        }

        /* Footer penyusun validator */
        .tbl-footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        .tbl-footer td {
            border: 1px solid #000;
            vertical-align: middle;
        }

        .tbl-footer .lbl {
            width: 35%;
            background: #fafafa;
        }

        .tbl-footer .sep {
            width: 4%;
            text-align: center;
        }

        .tbl-footer .val {
            width: 61%;
        }

        .tbl-footer .ttd-space {
            height: 40px;
        }
    </style>
</head>

<body>

    @php
$nama_skema = $nama_skema ?? '';
$kode_skema = $kode_skema ?? '';
$kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
$penyusun = $penyusun ?? [];
$validator = $validator ?? [];
    @endphp

    {{-- JUDUL --}}
    <div class="judul-utama">FR.MAPA.02. &nbsp; PETA INSTRUMEN ASESSMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN</div>

    {{-- SKEMA --}}
    <table class="tbl-skema">
        <colgroup>
            <col style="width:28%;">
            <col style="width:12%;">
            <col style="width:3%;">
            <col style="width:57%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="font-weight:bold; vertical-align:middle;">
                Skema Sertifikasi<br>
                <span style="font-weight:normal; font-size:8.5pt;">(KKNI/Okupasi/Klaster)</span>
            </td>
            <td>Judul</td>
            <td colspan="2" style="font-weight:bold;">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td colspan="2" style="font-weight:bold;">{{ $kode_skema }}</td>
        </tr>
    </table>

    {{-- LOOP KELOMPOK PEKERJAAN --}}
    @foreach($kelompok_pekerjaan as $kp)

        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        {{-- Tabel unit --}}
        <table class="tbl-unit">
            <colgroup>
                <col style="width:18%;">
                <col style="width:5%;">
                <col style="width:20%;">
                <col style="width:57%;">
            </colgroup>
            <tr>
                <th>Kelompok Pekerjaan {{ $kp['no'] }}</th>
                <th>No.</th>
                <th>Kode Unit</th>
                <th style="text-align:left; padding-left:8px;">Judul Unit</th>
            </tr>
            @foreach($kp['unit'] as $i => $unit)
                <tr>
                    @if($i === 0)
                        <td rowspan="{{ count($kp['unit']) }}" style="vertical-align:middle; text-align:center; font-size:8pt;">
                            {{ $kp['nama'] }}</td>
                    @endif
                    <td style="text-align:center;">{{ $i + 1 }}.</td>
                    <td>{{ $unit['kode'] }}</td>
                    <td>{{ $unit['judul'] }}</td>
                </tr>
            @endforeach
        </table>

        {{-- Tabel instrumen asesmen --}}
        <table class="tbl-instrumen">
            <colgroup>
                <col style="width:40px;">
                <col style="width:360px;">
                <col style="width:25px;">
                <col style="width:25px;">
                <col style="width:25px;">
                <col style="width:25px;">
                <col style="width:25px;">
            </colgroup>
            <tr>
                <th rowspan="2" colspan="1" style="width:30px; white-space:nowrap; padding:5px 3px;">No.</th>
                <th rowspan="2" colspan="3">INSTRUMEN ASESMEN</th>
                <th colspan="5">Potensi Asesi</th>
            <tr>
                <th style="width:25px; padding:2px;">1</th>
                <th style="width:25px; padding:2px;">2</th>
                <th style="width:25px; padding:2px;">3</th>
                <th style="width:25px; padding:2px;">4</th>
                <th style="width:25px; padding:2px;">5</th>
            </tr>
            @foreach($kp['instrumen'] as $index => $inst)
                <tr>
                    <td style="text-align:center; white-space:nowrap; padding:5px 3px;">{{ $index + 1 }}.</td>
                    <td colspan="3">{{ $inst['method'] ?? '' }}</td>
                    <td style="text-align:center; padding:5px 2px;"><span class="{{ $inst['answer'] === '1' ? 'cb-checked' : 'cb' }}"></span></td>
                    <td style="text-align:center; padding:5px 2px;"><span class="{{ $inst['answer'] === '2' ? 'cb-checked' : 'cb' }}"></span></td>
                    <td style="text-align:center; padding:5px 2px;"><span class="{{ $inst['answer'] === '3' ? 'cb-checked' : 'cb' }}"></span></td>
                    <td style="text-align:center; padding:5px 2px;"><span class="{{ $inst['answer'] === '4' ? 'cb-checked' : 'cb' }}"></span></td>
                    <td style="text-align:center; padding:5px 2px;"><span class="{{ $inst['answer'] === '5' ? 'cb-checked' : 'cb' }}"></span></td>
                </tr>
            @endforeach
        </table>

    @endforeach

    {{-- KETERANGAN POTENSI ASESI --}}
    <div class="keterangan">
        <strong>Keterangan Potensi Asesi:</strong>
        <ol>
            <li>Hasil pelatihan dan/atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap
                standar kompetensi.</li>
            <li>Hasil pelatihan dan/atau pendidikan, dimana kurikulum belum berbasis kompetensi.</li>
            <li>Pekerja berpengalaman dari industri/tempat kerja yang operasionalnya mampu telusur dengan standar
                kompetensi.</li>
            <li>Pekerja berpengalaman dari industri/tempat kerja yang operasionalnya belum berbasis kompetensi.</li>
            <li>Pelatihan / belajar mandiri atau otodidak.</li>
        </ol>
    </div>

    {{-- PENYUSUN & VALIDATOR --}}
    <div style="font-weight:bold; font-size:9pt; margin-bottom:2rem;">Penyusun dan Validator</div>
    <table class="tbl-footer">
        <colgroup>
            <col style="width:35%;">
            <col style="width:4%;">
            <col style="width:61%;">
        </colgroup>
        <tr>
            <td class="lbl">Nama Penyusun</td>
            <td class="sep">:</td>
            <td class="val">{{ $penyusun[0]['name'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">No. Reg</td>
            <td class="sep">:</td>
            <td class="val">{{ $penyusun[0]['reg_number'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">Tandatangan dan Tanggal</td>
            <td class="sep">:</td>
            <td class="val ttd-space">{{ $penyusun[0]['date'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">Nama Validator</td>
            <td class="sep">:</td>
            <td class="val">{{ $validator[0]['name'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">No. Reg.</td>
            <td class="sep">:</td>
            <td class="val">{{ $validator[0]['reg_number'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">Tandatangan dan Tanggal</td>
            <td class="sep">:</td>
            <td class="val ttd-space">{{ $validator[0]['date'] ?? '' }}</td>
        </tr>
    </table>

</body>

</html>
