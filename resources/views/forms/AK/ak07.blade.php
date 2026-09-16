<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.07</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            padding: 4rem
        }

        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }

        .page-break {
            page-break-before: always;
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
            margin-bottom: 0;
        }

        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8.5pt;
            line-height: 1.4;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 3px;
            flex-shrink: 0;
            position: relative;
        }

        .cb-checked::after {
            display: inline-block;
            content: '';
            position: absolute;
            left: 2px;
            top: -1px;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Panduan box */
        .panduan-box {
            border: 1px solid #000;
            padding: 7px 10px;
            margin-bottom: 10px;
        }

        .panduan-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 6px;
        }

        .panduan-item {
            margin-bottom: 4px;
            padding-left: 12px;
            text-indent: -12px;
            line-height: 1.4;
            font-size: 8pt;
        }

        .panduan-item::before {
            content: "• ";
        }

        /* Potensi asesi box */
        .potensi-tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }

        .potensi-tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 8.5pt;
            line-height: 1.4;
            word-wrap: break-word;
        }

        /* Bagian A tabel */
        .tbl-a {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8pt;
        }

        .tbl-a th {
            border: 1px solid #000;
            padding: 4px 5px;
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
        }

        .tbl-a td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8pt;
            line-height: 1.4;
        }

        /* cb-item: checkbox + teks dalam satu baris */
        .cb-item {
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .cb-item:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 8.5pt;
            margin: 8px 0 4px 0;
        }

        /* Bagian B tabel */
        .tbl-b {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8pt;
        }

        .tbl-b th {
            border: 1px solid #000;
            padding: 4px 5px;
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tbl-b td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8pt;
            line-height: 1.4;
        }

        .note {
            font-size: 7.5pt;
            font-style: italic;
            color: #333;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        /* Footer TTD */
        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
        }

        .tbl-ttd td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .ttd-space {
            height: 35px;
        }
    </style>
</head>

<body>

    @php
        $scheme_name = $scheme_name ?? '';
        $scheme_code = $scheme_code ?? '';
        $scheme_type = $scheme_type ?? '(KKNI/Okupasi/Klaster)';
        $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
        $data_part_a = $data_part_a ?? [];
        $data_part_b = $data_part_b ?? [];
        $asesor_name = $asesor_name ?? '';
        $asesi_name = $asesi_name ?? '';
        $assessment_date = $assessment_date ?? '';
        $asesi_potential = $asesi_potential ?? ''; // 1-5
    @endphp

    {{-- JUDUL --}}
    <div class="judul">FR.AK.07. CEKLIS PENYESUAIAN YANG WAJAR dan BERALASAN</div>

    {{-- INFO --}}
    <table class="tbl" style="margin-bottom:1rem;">
        <colgroup>
            <col style="width:28%;">
            <col style="width:14%;">
            <col style="width:3%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>{{ $scheme_type }}</td>
            <td colspan="1">Judul :</td>
            <td style="font-weight:bold;" colspan="5">{{ $scheme_name }}</td>
        </tr>
        <tr>
            <td colspan="1">Nomor :</td>
            <td style="font-weight:bold;" colspan="5">{{ $scheme_code }}</td>
        </tr>
        <tr>
            <td colspan="3">TUK :</td>
            <td colspan="5">{{ $tuk }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesor :</td>
            <td colspan="5">{{ $asesor_name }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesi :</td>
            <td colspan="5">{{ $asesi_name }}</td>
        </tr>
        <tr>
            <td colspan="3">Tanggal :</td>
            <td colspan="5">{{ $assessment_date }}</td>
        </tr>
    </table>

    {{-- PANDUAN --}}
    <div class="panduan-box">
        <div class="panduan-title">Panduan Bagi Asesor:</div>
        <div class="panduan-item">Lengkapi ceklis ini sebelum pelaksanaan asesmen.</div>
        <div class="panduan-item">Hasil penyesuaian yang wajar dan beralasan ini harus dibahas dan disepakati bersama antara asesi dan asesor.</div>
        <div class="panduan-item">Bila asesi tidak setuju dengan hasil penyesuaian ini maka asesi dapat melakukan banding (FR.AK.04).</div>
    </div>

    {{-- POTENSI ASESI --}}
    <table class="potensi-tbl">
        <colgroup>
            <col style="width:22%;">
            <col style="width:5%;">
            <col style="width:73%;">
        </colgroup>
        @foreach($asesi_potential as $index => $item)
            <tr>
                {{-- Hanya tampilkan kolom judul pada iterasi pertama --}}
                @if($loop->first)
                    <td rowspan="{{ count($asesi_potential) }}" colspan="3"
                        style="vertical-align:middle; font-weight:bold; text-align:center;">
                        Potensi Asesi
                    </td>
                @endif

                {{-- Kolom Checkbox --}}
                <td style="text-align:center; vertical-align:middle;" colspan="1">
                    <span class="{{ $item['is_checked'] ? 'cb-checked' : 'cb' }}"></span>
                </td>

                {{-- Kolom Label Dinamis --}}
                <td colspan="3">
                    {{ $item['label'] }}
                </td>
            </tr>
        @endforeach
    </table>

    {{-- BAGIAN A --}}
    <div class="section-title">Bagian A. Penyesuaian sesuai karakteristik asesi</div>
    <table class="tbl-a">
        <colgroup>
            <col style="width:5%;">
            <col style="width:30%;">
            <col style="width:5%;">
            <col style="width:5%;">
            <col style="width:55%;">
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" colspan="2">Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi (karakteristik
                    asesi):</th>
                <th colspan="2">Diperlukan penyesuaian</th>
                <th rowspan="2" colspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>Ya</th>
                <th>Tidak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_part_a as $item)
                <tr>
                    <td style="text-align:center;">{{ $item['id'] }}</td>
                    <td colspan="2">{{ $item['title'] }}</td>

                    {{-- Kolom Ya --}}
                    <td style="text-align:center;">
                        <span class="{{ $item['needs_adjustment'] ? 'cb-checked' : 'cb' }}"></span>
                    </td>

                    {{-- Kolom Tidak --}}
                    <td style="text-align:center;">
                        <span class="{{ !$item['needs_adjustment'] ? 'cb-checked' : 'cb' }}"></span>
                    </td>

                    <td colspan="2">
                        @foreach($item['note'] as $note)
                            <div class="cb-item">
                                {{-- Logika centang di dalam keterangan --}}
                                <span class="{{ ($note['is_checked'] ?? false) ? 'cb-checked' : 'cb' }}"></span>
                                {!! $note['title'] !!}
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- BAGIAN B --}}
    <div class="page-break"></div>
    <div class="section-title">Bagian B. Penyesuaian rencana asesmen sesuai acuan pembanding, potensi asesi dan konteks
        asesi.</div>

    <table class="tbl-b">
        <colgroup>
            <col style="width:40%;">
            <col style="width:7%;">
            <col style="width:7%;">
            <col style="width:46%;">
        </colgroup>
        <thead>
            <tr>
                <th colspan="2">Rekaman Rencana Asesmen</th>
                <th>Ya</th>
                <th>Tidak</th>
                <th>Keputusan Penyesuaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_part_b as $item)
                <tr>
                    <td colspan="2">{{ $item['id'] }}. {{ $item['question'] }}</td>

                    {{-- Kolom Ya --}}
                    <td style="text-align:center; vertical-align:middle;">
                        <span class="{{ ($item['answer'] ?? null) === true ? 'cb-checked' : 'cb' }}"></span>
                    </td>

                    {{-- Kolom Tidak --}}
                    <td style="text-align:center; vertical-align:middle;">
                        <span class="{{ ($item['answer'] ?? null) === false ? 'cb-checked' : 'cb' }}"></span>
                    </td>

                    {{-- Kolom Keputusan Penyesuaian (Dinamis berdasarkan ID) --}}
                    <td>
                        @if($item['id'] == 1)
                            <strong>Standar Industri:</strong>
                            {{ $item['adjustment_decisions']['industry_standard'] ?? '-' }}<br><br>
                            <strong>SOP (jika ada):</strong> {{ $item['adjustment_decisions']['sop'] ?? '-' }}<br><br>
                            <strong>Regulasi Teknik:</strong> {{ $item['adjustment_decisions']['technical_regulation'] ?? '-' }}
                        @else
                            <strong>Metode asesmen:</strong><br>
                            {{ $item['adjustment_decisions']['assessment_method'] ?? '-' }}<br><br>
                            <strong>Instrumen Asesmen:</strong><br>
                            FR.IA.{{ $item['adjustment_decisions']['fr_ia_assessment_instrument'] ?? '..........' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Ringkasan hasil --}}
    <table class="tbl" style="margin-bottom:10px;">
        <tr>
            <td style="line-height:1.7; font-size:8pt; padding: 10px;">
                <strong>Hasil Penyesuaian yang wajar dan beralasan disepakati menggunakan:</strong><br><br>

                <strong>A. Hasil penyesuaian sesuai karakteristik asesi</strong><br>
                <div style="margin-left: 15px;">
                    1) Acuan Pembanding Asesmen:
                    {{ $adjustment_results_a['assessment_benchmark'] ?? '( tuliskan nama acuan pembanding )' }}<br>
                    2) Metode Asesmen:
                    {{ $adjustment_results_a['assessment_method'] ?? '( tuliskan nama metode asesmen )' }}<br>
                    3) Instrumen Asesmen:
                    {{ $adjustment_results_a['assessment_instrument'] ?? '( tuliskan nama formulir instrumen asesmen )' }}
                </div>
                <br>

                <strong>B. Hasil penyesuaian rencana asesmen sesuai acuan pembanding, potensi asesi dan konteks
                    asesi</strong><br>
                <div style="margin-left: 15px;">
                    1) Acuan Pembanding Asesmen:
                    {{ $adjustment_results_b['assessment_benchmark'] ?? '( tuliskan nama acuan pembanding )' }}<br>
                    2) Metode Asesmen:
                    {{ $adjustment_results_b['assessment_method'] ?? '( tuliskan nama metode asesmen )' }}<br>
                    3) Instrumen Asesmen:
                    {{ $adjustment_results_b['assessment_instrument'] ?? '( tuliskan nama formulir instrumen asesmen )' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- TTD --}}
    <table class="tbl-ttd">
        <colgroup>
            <col style="width:30%;">
            <col style="width:20%;">
            <col style="width:30%;">
            <col style="width:20%;">
        </colgroup>
        <tr>
            <td>
                Nama Asesor : {{ $asesor_name }}<br>
                <span style="font-size: 8pt;">No. Reg: {{ $asesor_reg }}</span>
            </td>
            <td class="ttd-space" style="vertical-align: middle; padding: 6px;">
                Tanggal dan Tanda Tangan Asesor:<br>
                <div style="margin-top: 10px;">
                    @if($asesor_qr_code)
                        <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px; vertical-align: middle;">
                    @endif
                    <span style="vertical-align: middle; margin-left: 8px;">
                        {{ $asesor_sign_date }}
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Nama Asesi : {{ $asesi_name }}</td>
            <td class="ttd-space">Tanggal dan Tanda Tangan Asesi: {{ $asesi_sign_date }}</td>
        </tr>
    </table>

</body>

</html>
