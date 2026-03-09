{{-- resources/views/forms/IA/ia05a.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.05A - Pertanyaan Tertulis Pilihan Ganda</title>
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

        .form-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Header table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
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
            margin-bottom: 6px;
        }

        .instruksi {
            font-size: 9.5pt;
            margin-bottom: 10px;
        }

        /* Soal table */
        .soal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .soal-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .soal-table .no-cell {
            width: 5%;
            font-weight: bold;
            vertical-align: top;
            padding-top: 5px;
        }
        .soal-table .content-cell {
            width: 95%;
        }
        .soal-table .pilihan-row td {
            border-top: none;
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .soal-table .pilihan-label {
            width: 5%;
            padding-left: 16px;
        }
        .soal-table .pilihan-value {
            width: 95%;
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
        .pv-table .status-cell { font-weight: bold; }
        .pv-table .ttd-cell    { height: 40px; }
    </style>
</head>
<body>
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">FR.IA.05A. DPT – PERTANYAAN TERTULIS PILIHAN GANDA</div>

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

    {{-- SOAL --}}
    <table class="soal-table">
        <tbody>
            @foreach ($soal as $s)
            {{-- Baris nomor + pertanyaan --}}
            <tr>
                <td class="no-cell">{{ $s['no'] }}.</td>
                <td class="content-cell" style="padding-bottom: 4px;">{{ $s['pertanyaan'] }}</td>
            </tr>
            {{-- Pilihan a, b, c, d --}}
            @foreach ($s['pilihan'] as $huruf => $teks)
            <tr class="pilihan-row">
                <td class="no-cell" style="border-top:none; font-weight:normal; padding-left:14px">{{ $huruf }}.</td>
                <td class="content-cell pilihan-value" style="border-top:none;">{{ $teks }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
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
                <th style="width:22%">TANDA TANGAN DAN TANGGAL</th>
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