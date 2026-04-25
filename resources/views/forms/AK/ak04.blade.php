<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.04</title>
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
            padding: 4rem
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
        }

        .tbl td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 9pt;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            position: relative;
        }

        .cb-checked::after {
            display: inline-block;
            content: '';
            position: absolute;
            left: 4px;
            top: 0px;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>
</head>

<body>

    @php
$asesi_name = $asesi_name ?? '';
$asesor_name = $asesor_name ?? '';
$assessment_date = $assessment_date ?? '';
$scheme_name = $scheme_name ?? '';
$scheme_code = $scheme_code ?? '';
$reason_notes = $reason_notes ?? '';
$asesi_sign = $asesi_sign ?? '';
$asesi_sign_date = $asesi_sign_date ?? '';
$appeal_questions = $appeal_questions ?? '';
    @endphp

    <div class="judul">FR.AK.04. &nbsp; BANDING ASESMEN</div>

    <table class="tbl">
        {{-- 6 kolom: label(60%) | spacer | Ya-label(8%) | Ya-cb(4%) | Tidak-label(8%) | Tidak-cb(4%) --}}
        <colgroup>
            <col style="width:56%;">
            <col style="width:4%;">
            <col style="width:10%;">
            <col style="width:6%;">
            <col style="width:10%;">
            <col style="width:6%;">
            <col style="width:8%;">
        </colgroup>

        {{-- Nama Asesi --}}
        <tr>
            <td colspan="7">Nama Asesi: {{ $asesi_name }}</td>
        </tr>

        {{-- Nama Asesor --}}
        <tr>
            <td colspan="7">Nama Asesor: {{ $asesor_name }}</td>
        </tr>

        {{-- Tanggal --}}
        <tr>
            <td colspan="7">Tanggal Asesmen: {{ $assessment_date }}</td>
        </tr>

        {{-- Header Ya/Tidak --}}
        <tr>
            <td colspan="5" style="font-style:italic;">Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :
            </td>
            <td colspan="1" style="text-align:center; font-weight:bold;">YA</td>
            <td style="text-align:center; font-weight:bold;">TIDAK</td>
        </tr>

        {{-- Pertanyaan 1 --}}
        <tr>
            <td colspan="5">Apakah Proses Banding telah dijelaskan kepada Anda?</td>
            <td colspan="1" style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[1] === true) ? 'cb-checked' : 'cb' }}"></span></td>
            <td style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[1] === false) ? 'cb-checked' : 'cb' }}"></span></td>
        </tr>

        {{-- Pertanyaan 2 --}}
        <tr>
            <td colspan="5">Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
            <td colspan="1" style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[2] === true) ? 'cb-checked' : 'cb' }}"></span></td>
            <td style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[2] === false) ? 'cb-checked' : 'cb' }}"></span></td>
        </tr>

        {{-- Pertanyaan 3 --}}
        <tr>
            <td colspan="5">Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?</td>
            <td colspan="1" style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[3] === true) ? 'cb-checked' : 'cb' }}"></span></td>
            <td style="text-align:center; vertical-align:middle;"><span
                    class="{{ ($appeal_questions[3] === false) ? 'cb-checked' : 'cb' }}"></span></td>
        </tr>

        {{-- Info Skema --}}
        <tr>
            <td colspan="7" style="line-height:1.8;">
                Banding ini diajukan atas Asesmen yang dibuat terhadap Skema Sertifikasi Keputusan
                (Kualifikasi/Klaster/Okupasi) berikut :<br>
                Skema Sertifikasi &nbsp;&nbsp;&nbsp; : {{ $scheme_name }}<br>
                No. Skema Sertifikasi &nbsp; : {{ $scheme_code }}
            </td>
        </tr>

        {{-- Alasan --}}
        <tr>
            <td colspan="7" style="height:80px; vertical-align:top;">
                Banding ini diajukan atas alasan sebagai berikut :<br><br>
                {{ $reason_notes }}
            </td>
        </tr>

        {{-- Hak banding --}}
        <tr>
            <td colspan="7" style="line-height:1.6; font-size:8.5pt;">
                Anda mempunyai hak mengajukan banding jika Anda menilai Proses Asesmen tidak sesuai SOP dan tidak
                memenuhi Prinsip Asesmen.
            </td>
        </tr>

        {{-- TTD --}}
        <tr>
            <td colspan="7" style="height:50px; vertical-align:middle; padding-bottom:6px;">
                Tanda tangan Asesi : {{ $asesi_name }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="margin-left: 1rem;">Tanggal : {{ $asesi_sign_date }}</span>
            </td>
        </tr>

    </table>

</body>

</html>