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

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 9pt;
        }
        .tbl .label { width: 28%; }
        .tbl .sub-label { width: 14%; }
        .tbl .colon { width: 3%; text-align: center; }

        .note-small {
            font-size: 8.5pt;
            margin-bottom: 12px;
        }

        .instruction {
            font-size: 10pt;
            margin-bottom: 5px;
        }

        /* Soal table - Match screenshot */
        .soal-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .soal-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 10pt;
            vertical-align: top;
            word-wrap: break-word;
        }
        .soal-table .no-cell {
            width: 5%;
            border-right: none;
        }
        .soal-table .content-cell {
            width: 95%;
            border-left: none;
        }
        .pilihan-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .pilihan-item {
            margin-bottom: 2px;
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
            page-break-inside: avoid;
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
            background-color: #fff;
        }
        .pv-table .status-cell { font-weight: bold; }
        .pv-table .ttd-cell    { height: 45px; }

        .signature-section {
            page-break-inside: avoid;
            margin-bottom: 2rem;
        }

        .page-break {
            page-break-before: always;
        }

         /* Header table */
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

        .sig-block {
            text-align: left;
        }
        .sig-image {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
@php
    $judul = $judul ?? '';
    $nomor = $nomor ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">FR.IA.05A. DPT – PERTANYAAN TERTULIS PILIHAN GANDA</div>

    <table class="tbl" style="page-break-inside: avoid;">
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

    <div class="instruction">Jawab semua pertanyaan berikut:</div>

    {{-- SOAL --}}
    <table class="soal-table">
        <tbody>
            @foreach ($soal as $s)
            {{-- Baris 1: Nomor dan Pertanyaan --}}
            <tr style="page-break-inside: avoid;">
                <td class="no-cell" style="font-weight:bold;">{{ $loop->iteration }}.</td>
                <td class="content-cell" style="font-weight:bold;">{{ $s['pertanyaan'] }}</td>
            </tr>
            {{-- Baris 2: Pilihan Jawaban --}}
            <tr style="page-break-inside: avoid;">
                <td class="no-cell" style="border-top:none;"></td>
                <td class="content-cell" style="border-top:none; padding-top: 8px; padding-bottom: 8px;">
                    @foreach ($s['pilihan'] as $huruf => $teks)
                        <div class="pilihan-item">{{ $huruf }}. {{ $teks }}</div>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        {{-- TANDA TANGAN --}}
        <table style="width:100%; border-collapse:collapse; margin-bottom: 1rem; page-break-inside: avoid;">
            <tr>
                <td style="width:50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                    <strong>Tanda Tangan Asesi:</strong><br><br>
                    <div class="sig-block">
                        @if($asesi_signature ?? null)
                            <img src="{{ $asesi_signature }}" class="sig-image" style="max-height: 70px;">
                        @else
                            <br><br><br>
                        @endif
                        Nama : {{ $nama_asesi ?? '' }}<br>
                        Tanggal: {{ $tanggal ?? '' }}
                    </div>
                </td>
                <td style="width:50%; border: 1px solid #000; padding: 10px; vertical-align: top;">
                    <strong>Tanda Tangan Asesor:</strong><br><br>
                    <div class="sig-block">
                        @if($asesor_qr_code ?? null)
                            <img src="{{ $asesor_qr_code }}" class="sig-image" style="width: 50px; height: 50px;">
                        @elseif($asesor_signature ?? null)
                            <img src="{{ $asesor_signature }}" class="sig-image" style="max-height: 70px;">
                        @else
                            <br><br><br>
                        @endif
                        Nama : {{ $nama_asesor ?? '' }}<br>
                        Tanggal: {{ $tanggal ?? '' }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- PENYUSUN DAN VALIDATOR --}}
        <div style="page-break-inside: avoid;">
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
                        <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
                        <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
                        <td class="ttd-cell">
                            <div class="sig-block" style="text-align: center;">
                                @if($p['qr_code'] ?? null)
                                    <img src="{{ $p['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                                @elseif($p['signature'] ?? null)
                                    <img src="{{ $p['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                                @endif
                                <div>{{ $p['date'] ?? '' }}</div>
                            </div>
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
                            <div class="sig-block" style="text-align: center;">
                                @if($v['qr_code'] ?? null)
                                    <img src="{{ $v['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                                @elseif($v['signature'] ?? null)
                                    <img src="{{ $v['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                                @endif
                                <div>{{ $v['date'] ?? '' }}</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
