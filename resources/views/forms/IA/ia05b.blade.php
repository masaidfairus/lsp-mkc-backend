{{-- resources/views/forms/IA/ia05b.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.05B - Lembar Kunci Jawaban Pertanyaan Tertulis Pilihan Ganda</title>
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
            margin-bottom: 12px;
        }

        /* Unit table */
        .unit-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
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

        /* Kunci jawaban label */
        .kunci-label {
            font-size: 9.5pt;
            margin-bottom: 6px;
        }

        /* Kunci jawaban table — 2 kolom side by side */
        .kunci-wrapper {
            width: 100%;
            margin-bottom: 16px;
        }
        .kunci-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kunci-table th, .kunci-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .kunci-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }
        .kunci-table .col-no     { width: 15%; text-align: center; }
        .kunci-table .col-jawab  { width: 35%; }

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
    <div class="form-title">FR.IA.05B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA</div>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width:28%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)*</td>
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
    </table>

    {{-- TABEL UNIT KOMPETENSI --}}
    <table class="unit-table">
        <thead>
            <tr>
                <th style="width:28%">Kelompok Pekerjaan</th>
                <th style="width:8%">No.</th>
                <th style="width:22%">Kode Unit</th>
                <th>Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelompok as $kp)
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
            @endforeach
        </tbody>
    </table>

    {{-- KUNCI JAWABAN --}}
    <div class="kunci-label">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</div>

    @php
        // Bagi kunci jawaban jadi 2 kolom
        $half   = (int) ceil(count($kunci_jawaban) / 2);
        $kolom1 = array_slice($kunci_jawaban, 0, $half);
        $kolom2 = array_slice($kunci_jawaban, $half);
    @endphp

    <table class="kunci-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-jawab">Kunci Jawaban</th>
                <th class="col-no">No.</th>
                <th class="col-jawab">Kunci Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < $half; $i++)
            <tr>
                <td class="col-no">{{ $kolom1[$i]['no'] }}</td>
                <td class="col-jawab">{{ $kolom1[$i]['jawaban'] }}</td>
                <td class="col-no">{{ isset($kolom2[$i]) ? $kolom2[$i]['no'] : '' }}</td>
                <td class="col-jawab">{{ isset($kolom2[$i]) ? $kolom2[$i]['jawaban'] : '' }}</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <br>

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