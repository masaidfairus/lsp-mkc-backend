{{-- resources/views/forms/AK/ak05.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.05 - Laporan Asesmen</title>
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
            margin-bottom: 14px;
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

        /* Tabel Asesi */
        .asesi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .asesi-table th,
        .asesi-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            vertical-align: middle;
        }

        .asesi-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        .asesi-table .col-no {
            width: 7%;
            text-align: center;
        }

        .asesi-table .col-nama {
            width: 38%;
        }

        .asesi-table .col-k {
            width: 9%;
            text-align: center;
        }

        .asesi-table .col-bk {
            width: 9%;
            text-align: center;
        }

        .asesi-table .col-ket {
            width: 37%;
        }

        .asesi-table .data-row td {
            height: 30px;
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

        .note-keterangan {
            font-size: 8.5pt;
            margin-bottom: 6px;
        }

        /* Tabel Aspek */
        .aspek-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .aspek-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            vertical-align: top;
        }

        .aspek-table .label-col {
            width: 35%;
        }

        .aspek-table .value-col {
            width: 65%;
            min-height: 40px;
            height: 40px;
        }

        /* Catatan + Asesor side by side */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .bottom-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            vertical-align: top;
        }

        .bottom-table .catatan-cell {
            width: 45%;
            height: 110px;
        }

        .bottom-table .asesor-block {
            width: 55%;
            padding: 0;
        }

        /* Tabel asesor di dalam bottom-table */
        .asesor-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .asesor-inner td {
            border: 0;
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: top;
        }

        .asesor-inner .label {
            width: 40%;
        }

        .asesor-inner .value {
            width: 60%;
        }

        .asesor-inner .ttd-row td {
            height: 42px;
        }

        .asesor-inner .header-row td {
            font-weight: bold;
            background-color: #e8e8e8;
            border-top: 0;
        }
    </style>
</head>

<body>

    {{-- JUDUL --}}
    <div class="form-title">FR.AK.05.&nbsp;&nbsp;&nbsp; LAPORAN ASESMEN</div>

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
            <td colspan="2">Tanggal</td>
            <td style="text-align:center">:</td>
            <td>{{ $assessor_signature['date'] ?? '' }}</td>
        </tr>
    </table>

    {{-- TABEL ASESI --}}
    <table class="asesi-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No.</th>
                <th class="col-nama" rowspan="2">Nama Asesi</th>
                <th colspan="2">Rekomendasi</th>
                <th class="col-ket" rowspan="2">Keterangan**</th>
            </tr>
            <tr>
                <th class="col-k">K</th>
                <th class="col-bk">BK</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            @foreach ($assessment_results as $index => $a)
                <tr class="data-row">
                    <td class="col-no">{{ $index + 1 }}.</td>
                    <td>{{ $a['assessee_name'] ?? '' }}</td>
                    <td class="col-k"><span style="font-size: 13pt;">{!! ($a['recommendation'] ?? '') === 'K' ? '☑' : '☐' !!}</span></td>
                    <td class="col-bk"><span style="font-size: 13pt;">{!! ($a['recommendation'] ?? '') === 'BK' ? '☑' : '☐' !!}</span></td>
                    <td>{{ $a['description'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TABEL ASPEK --}}
    <table class="aspek-table">
        <tr>
            <td class="label-col">Aspek Negatif dan Positif dalam Asesemen</td>
            <td class="value-col">{{ $evaluations['positive_negative_aspects'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Pencatatan Penolakan Hasil Asesmen</td>
            <td class="value-col">{{ $evaluations['rejection_records'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Saran Perbaikan :<br>(Asesor/Personil Terkait)</td>
            <td class="value-col">{{ $evaluations['improvement_suggestions'] ?? '' }}</td>
        </tr>
    </table>

    {{-- CATATAN + ASESOR --}}
    <table class="bottom-table">
        <tr>
            <td class="catatan-cell">
                <strong>Catatan :</strong><br><br>
                {{ $assessor_signature['notes'] ?? '' }}
            </td>
            <td class="asesor-block">
                <table class="asesor-inner">
                    <tr class="header-row">
                        <td colspan="2">Asesor :</td>
                    </tr>
                    <tr>
                        <td class="label">Nama</td>
                        <td class="value">{{ $asesor_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. Reg</td>
                        <td class="value">{{ $asesor_reg }}</td>
                    </tr>
                    <tr class="ttd-row">
                        <td class="label" rowspan="3">Tanda tangan/<br>Tanggal</td>
                        <td class="value" rowspan="3" style="vertical-align: middle; padding: 5px;">
                            @if($asesor_qr_code)
                                <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px; vertical-align: middle;">
                            @endif
                            <span style="vertical-align: middle; margin-left: 8px;">
                                {{ $assessor_signature['date'] ?? '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>