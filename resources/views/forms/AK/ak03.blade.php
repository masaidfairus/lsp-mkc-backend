{{-- resources/views/forms/AK/ak03.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.03 - Umpan Balik dan Catatan Asesmen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
        }

        .page {
            width: 100%;
        }

        .page-break {
            page-break-before: always;
        }


        .form-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }

        .header-table .val {
            font-weight: bold;
        }

        .instruksi {
            font-size: 9pt;
            margin-bottom: 8px;
        }

        /* Tabel Komponen */
        .komponen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .komponen-table th,
        .komponen-table td {
            border: 1px solid #000;
            font-size: 9pt;
            padding: 5px 7px;
            vertical-align: middle;
        }

        .komponen-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        .col-komponen {
            width: 62%;
            text-align: left;
        }

        .col-ya {
            width: 7%;
            text-align: center;
        }

        .col-tidak {
            width: 7%;
            text-align: center;
        }

        .col-catatan {
            width: 24%;
            text-align: left;
            vertical-align: top;
        }

        .komponen-row td {
            height: 32px;
            vertical-align: middle;
        }

        .catatan-row td {
            height: 60px;
            vertical-align: top;
        }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            position: relative;
        }

        .checkbox.checked::after {
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

        /* TTD */
        .ttd-table {
            width: 55%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .ttd-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: top;
        }

        .ttd-table .section-header td {
            font-weight: bold;
            background-color: #e8e8e8;
        }

        .ttd-table .ttd-row td {
            height: 42px;
        }
    </style>
</head>

<body>
    <div class="page">

        {{-- JUDUL --}}
        <div class="form-title">FR.AK.03.&nbsp; UMPAN BALIK DAN CATATAN ASESMEN</div>

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td style="width:26%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
                <td style="width:10%">Judul</td>
                <td style="width:3%; text-align:center">:</td>
                <td class="val">{{ $scheme_name }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td style="text-align:center">:</td>
                <td class="val">{{ $scheme_code }}</td>
            </tr>
            <tr>
                <td colspan="2">TUK</td>
                <td style="text-align:center">:</td>
                <td>{{ $tuk }}</td>
            </tr>
            <tr>
                <td colspan="2">Nama Asesor</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesor_name }}</td>
            </tr>
            <tr>
                <td colspan="2">Nama Asesi</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesi_name }}</td>
            </tr>
            <tr>
                <td rowspan="2">Tanggal Asesmen</td>
                <td>Mulai</td>
                <td style="text-align:center">:</td>
                <td>{{ $assessment_date_start }}</td>
            </tr>
            <tr>
                <td>Selesai</td>
                <td style="text-align:center">:</td>
                <td>{{ $assessment_date_end }}</td>
            </tr>
        </table>

        {{-- INSTRUKSI --}}
        <div class="instruksi">Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan):</div>

        {{-- TABEL KOMPONEN --}}
        <table class="komponen-table">
            <thead>
                <tr>
                    <th class="col-komponen">KOMPONEN</th>
                    <th colspan="2">Hasil</th>
                    <th class="col-catatan">Catatan/Komentar<br>Asesi</th>
                </tr>
                <tr>
                    <th class="col-komponen" style="background:#fff; font-weight:normal"></th>
                    <th class="col-ya">Ya</th>
                    <th class="col-tidak">Tidak</th>
                    <th class="col-catatan" style="background:#fff; font-weight:normal"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedback_items as $item)
                    <tr class="komponen-row">
                        <td class="col-komponen">{{ $item['component'] ?? '-' }}</td>
                        <td class="col-ya"><span style="font-size: 13pt;">{!! !empty($item['result_yes']) ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span></td>
                        <td class="col-tidak"><span style="font-size: 13pt;">{!! !empty($item['result_no']) ? '<span style="font-family: DejaVu Sans, sans-serif;">☑</span>' : '<span style="font-family: DejaVu Sans, sans-serif;">☐</span>' !!}</span></td>
                        <td class="col-catatan">{{ $item['assessee_note'] ?? '' }}</td>
                    </tr>
                @endforeach

                {{-- Catatan/komentar lainnya --}}
                <tr class="catatan-row">
                    <td colspan="4" style="vertical-align:top; padding: 5px 7px;">
                        <strong>Catatan/komentar lainnya (apabila ada) :</strong><br><br>
                        {{ $notes ?? '' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page-break"></div>
        {{-- TTD --}}
        <table class="ttd-table">
            <tr class="section-header">
                <td colspan="3"><strong>Asesi :</strong></td>
            </tr>
            <tr>
                <td style="width:38%">Nama</td>
                <td style="width:4%; text-align:center">:</td>
                <td>{{ $asesi_name }}</td>
            </tr>
            <tr class="ttd-row">
                <td>Tanda tangan<br>dan Tanggal</td>
                <td style="text-align:center">:</td>
                <td>
                    @if(($signatures['assessee']['signature'] ?? null)) <img
                    src="{{ $signatures['assessee']['signature'] }}" style="max-height:40px;"> <br> @endif
                    {{ $signatures['assessee']['date'] ?? '' }}
                </td>
            </tr>
            <tr class="section-header">
                <td colspan="3"><strong>Asesor :</strong></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesor_name }}</td>
            </tr>
            <tr>
                <td>No. Reg</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesor_reg }}</td>
            </tr>
            <tr class="ttd-row">
                <td>Tanda tangan<br>dan Tanggal</td>
                <td style="text-align:center">:</td>
                <td style="vertical-align: middle; padding: 10px;">
                    @if($asesor_qr_code)
                        <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px; vertical-align: middle;">
                    @endif
                    <span style="vertical-align: middle; margin-left: 10px;">
                        {{ $signatures['assessor']['date'] ?? '' }}
                    </span>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
