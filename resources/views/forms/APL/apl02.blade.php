<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.APL.02 - Asesmen Mandiri</title>
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
            background: #fff;
            padding: 6rem;
        }

        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }

        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .tbl-border {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
        }

        .tbl-border td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 12px;
        }

        .panduan-box .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .panduan-box ul {
            margin-left: 16px;
        }

        .panduan-box ul li {
            margin-bottom: 4px;
            line-height: 1.4;
        }

        /*
            SATU tabel, 6 kolom:
            col1: Unit Kompetensi = 22%
            col2: Label (Kode/Judul Unit) = 14%
            col3: Titik dua = 3%
            col4: Nilai / Dapatkah Saya = 37%
            col5: K = 6%
            col6: BK = 6%
            col7: Bukti = 12%
            Total = 100%
        */
        .tbl-unit {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 14px;
            table-layout: fixed;
        }

        .tbl-unit td,
        .tbl-unit th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .tbl-unit th {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .checkbox-checked {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
            position: relative;
        }

        .checkbox-checked::after {
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

        .checkmark-text {
            display: inline-block;
            width: 10px;
            height: 10px;
            position: relative;
            vertical-align: middle;
        }

        .checkmark-text::after {
            content: '';
            position: absolute;
            left: 3px;
            top: -1px;
            width: 3px;
            height: 7px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .elemen-nama {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .kriteria-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .kriteria-item {
            margin-left: 8px;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .tbl-rekom {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }

        .tbl-rekom td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    @php
        $unit_kompetensi = $unit_kompetensi ?? [];
    @endphp

    <div class="judul-utama">FR.APL.02. ASESMEN MANDIRI</div>

    <table class="tbl-border" style="margin-bottom:12px;">
        <tr>
            <td rowspan="2" style="width:35%; vertical-align:middle;">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td style="width:15%;">Judul</td>
            <td style="width:3%; text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $nama_skema ?? '' }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $kode_skema ?? '' }}</td>
        </tr>
    </table>

    <div class="panduan-box">
        <div class="panduan-title">PANDUAN ASESMEN MANDIRI</div>
        <div style="font-weight:bold; margin-bottom:4px;">Instruksi:</div>
        <ul>
            <li>Baca setiap pertanyaan di kolom sebelah kiri</li>
            <li>Beri tanda centang (<span class="checkmark-text"></span>) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.</li>
            <li>Isi kolom di sebelah kanan dengan menuliskan bukti yang relevan anda miliki untuk menunjukkan bahwa anda
                melakukan pekerjaan.</li>
        </ul>
    </div>

    @foreach($unit_kompetensi as $unit)

        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        <table class="tbl-unit">
            {{--
            7 kolom:
            [22%] [14%] [3%] [37%] [6%] [6%] [12%]
            --}}
            <colgroup>
                <col style="width:22%;">
                <col style="width:14%;">
                <col style="width:3%;">
                <col style="width:37%;">
                <col style="width:6%;">
                <col style="width:6%;">
                <col style="width:12%;">
            </colgroup>

            {{-- Baris 1: Unit Kompetensi + Kode Unit --}}
            <tr>
                <td colspan="2" rowspan="2" style="font-weight:bold; vertical-align:middle;">
                    Unit Kompetensi {{ $loop->iteration }}
                </td>
                <td colspan="2">Kode Unit:</td>
                <td colspan="5">{{ $unit['unit_code'] }}</td>
            </tr>

            {{-- Baris 2: Judul Unit --}}
            <tr>
                <td colspan="2">Judul Unit:</td>
                <td colspan="5">{{ $unit['unit_title'] }}</td>
            </tr>

            {{-- Header kolom: Dapatkah Saya span 4 kolom pertama --}}
            <tr>
                <th colspan="4" style="text-align:left;">Dapatkah Saya.............?</th>
                <th>K</th>
                <th>BK</th>
                <th colspan="3">Bukti yang relevan</th>
            </tr>

            {{-- Loop elemen --}}
            @foreach($unit['competencyElements'] as $elemen)
                @php
                    $jawaban = $asesmen_mandiri->get($elemen['id']);
                    $isCompetent = isset($jawaban['is_competent']) ? $jawaban['is_competent'] : null;
                @endphp
                <tr>
                    <td colspan="4">
                        <div class="elemen-nama">
                            {{ $loop->iteration }}&nbsp;&nbsp;Elemen: <span
                                style="font-weight:normal;">{{ $elemen['title'] }}</span>
                        </div>
                        <div class="kriteria-label">Kriteria Unjuk Kerja:</div>
                        @foreach($elemen['performanceCriteria'] as $kri)
                            <div class="kriteria-item">
                                {{ $loop->iteration . '.'}} {{ $kri['name'] }}&nbsp;&nbsp;&nbsp;{{ $kri['description'] }}
                            </div>
                        @endforeach
                    </td>
                    <td style="text-align:center; vertical-align:middle;"><span
                            class="{{ $isCompetent === true ? 'checkbox-checked' : 'checkbox' }}"></span></td>
                    <td style="text-align:center; vertical-align:middle;"><span
                            class="{{ $isCompetent === false ? 'checkbox-checked' : 'checkbox' }}"></span></td>
                    <td colspan="3" style="font-size: 8.5pt; vertical-align: top; padding: 6px 4px; width: 25%;">
                        {{-- Cek apakah ada file di dalam koleksi portfolios --}}
                        @if($portfolios && $portfolios->count() > 0)
                            <ul style="margin: 0; padding-left: 12px; list-style-type: disc; line-height: 1.4;">
                                @foreach($portfolios as $title)
                                    <li style="margin-bottom: 5px; word-wrap: break-word;">
                                        {{ $title }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div style="color: #999; font-style: italic; text-align: center; padding-top: 5px;">
                                Belum ada bukti
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

    @endforeach

    <table class="tbl-rekom">
        <tr>
            <td style="width:40%;">
                <div style="font-weight:bold; margin-bottom:6px;">Rekomendasi Untuk Asesi:</div>
                <div style="margin-bottom: 8px;">
                    Asesmen <strong>{{ $is_apl02_continuable ? 'dapat' : 'tidak dapat' }}</strong> dilanjutkan
                </div>
                <div>
                    melalui pendekatan: <br>
                    <strong>{{ $apl02_approach ?? '.............................................' }}</strong>
                </div>
            </td>
            <td style="width:60%; padding:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">Asesi :
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; width:35%;">Nama</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">{{ $nama_asesi ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; height:80px; vertical-align:top;">
                            Tanda tangan/<br>Tanggal</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; vertical-align:middle; text-align:center;">
                            @if(!empty($asesi_qr_code))
                                <img src="{{ $asesi_qr_code }}" style="width:50px; height:50px; vertical-align: middle;">
                                <div style="vertical-align: middle; font-size: 8pt; margin-top: 6px;">
                                    {{ $asesi_date ?? '' }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">
                            Ditinjau Oleh Asesor :</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">Nama :</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">{{ $nama_asesor ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">No. Reg:</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">{{ $no_reg_asesor ?? '' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 6px; height:80px; vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                        <td style="padding:4px 6px; vertical-align:middle; text-align:center;">
                            @if($asesor_qr_code)
                                <img src="{{ $asesor_qr_code }}" style="width:50px; height:50px; vertical-align: middle;">
                                <div style="vertical-align: middle; font-size: 8pt; margin-top: 6px;">
                                    {{ $asesor_date ?? '' }}
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
