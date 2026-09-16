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

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .judul {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 5px;
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

        /* Unit table */
        .unit-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 12px;
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
            background-color: #fff;
        }

        /* Kunci jawaban table — 2 kolom side by side */
        .kunci-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .kunci-table th, .kunci-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: middle;
            word-wrap: break-word;
            text-align: center;
        }
        .kunci-table th {
            font-weight: bold;
        }
        .col-no     { width: 15%; }
        .col-jawab  { width: 35%; font-weight: bold; }

        /* Signatures block */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: avoid;
        }
        .bottom-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 9pt;
        }
        .section-header {
            font-weight: bold;
            background-color: #fff;
        }

        .pv-title {
            font-weight: bold;
            font-size: 10pt;
            margin: 15px 0 5px 0;
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
            font-size: 9pt;
            vertical-align: middle;
            text-align: center;
        }
        .pv-table th {
            font-weight: bold;
            background-color: #e8e8e8;
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
    $nama_asesi = $nama_asesi ?? '';
    $nama_asesor = $nama_asesor ?? '';
    $asesor_reg = $asesor_reg ?? '';
    $tanggal = $tanggal ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="judul">FR.IA.05.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA</div>

    <table class="tbl" style="margin-bottom: 1rem; page-break-inside: avoid;">
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
    </table>

{{-- TABEL UNIT KOMPETENSI --}}
<table class="unit-table" style="page-break-inside: avoid;">
    <thead>
        <tr>
            <th style="width:25%">Kelompok Pekerjaan</th>
            <th style="width:5%">No.</th>
            <th style="width:20%">Kode Unit</th>
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
                <td style="text-align:center">{{ !empty($unit['no']) ? $unit['no'] : ($i + 1) }}.</td>
                <td>{{ $unit['kode'] }}</td>
                <td>{{ $unit['judul'] }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<div style="font-weight:normal; margin-bottom: 5px;">Kunci Jawaban Pertanyaan Tertulis – Pilihan Ganda:</div>

@php
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
            <td class="col-no">{{ $kolom1[$i]['no'] }}.</td>
            <td class="col-jawab">{{ strtoupper($kolom1[$i]['jawaban']) }}</td>
            <td class="col-no">{{ isset($kolom2[$i]) ? $kolom2[$i]['no'] . '.' : '' }}</td>
            <td class="col-jawab">{{ isset($kolom2[$i]) ? strtoupper($kolom2[$i]['jawaban']) : '' }}</td>
        </tr>
        @endfor
    </tbody>
</table>

{{-- TANDA TANGAN (sama seperti C) --}}
<table class="bottom-table">
    <tr>
        <td colspan="2" class="section-header">Asesi :</td>
    </tr>
    <tr>
        <td style="width:35%;">Nama</td>
        <td style="width:65%;">: {{ $nama_asesi }}</td>
    </tr>
    <tr>
        <td>Tanda tangan/Tanggal</td>
        <td>
            <table style="width: 100%; border: none; border-collapse: collapse; margin: 0; padding: 0;">
                <tr>
                    <td style="width: 10px; border: none; padding: 0; vertical-align: top;">:</td>
                    <td style="border: none; padding: 0;">
                        <div class="sig-block">
                            @if($asesi_signature)
                                <img src="{{ $asesi_signature }}" class="sig-image" style="max-height: 40px;">
                            @endif
                            <div>{{ $tanggal }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="section-header">Asesor :</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: {{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td>No. Reg</td>
        <td>: {{ $asesor_reg ?? '' }}</td>
    </tr>
    <tr>
        <td>Tanda tangan/Tanggal</td>
        <td>
            <table style="width: 100%; border: none; border-collapse: collapse; margin: 0; padding: 0;">
                <tr>
                    <td style="width: 10px; border: none; padding: 0; vertical-align: top;">:</td>
                    <td style="border: none; padding: 0;">
                        <div class="sig-block">
                            @if($asesor_qr_code)
                                <img src="{{ $asesor_qr_code }}" class="sig-image" style="width: 45px; height: 45px;">
                            @elseif($asesor_signature)
                                <img src="{{ $asesor_signature }}" class="sig-image" style="max-height: 40px;">
                            @endif
                            <div>{{ $tanggal }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

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
                <td rowspan="{{ count($penyusun) }}" class="status-cell">PENYUSUN</td>
                @endif
                <td>{{ $i + 1 }}</td>
                <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
                <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
                <td>
                    <div class="sig-block" style="text-align: center;">
                        @if($p['qr_code'] ?? null)
                            <img src="{{ $p['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                        @elseif($p['signature'] ?? null)
                            <img src="{{ $p['signature'] }}" class="sig-image" style="max-height: 30px; margin: 0 auto 5px auto;">
                        @endif
                        <div>{{ $p['date'] ?? '' }}</div>
                    </div>
                </td>
            </tr>
            @endforeach
            @foreach ($validator as $i => $v)
            <tr>
                @if ($i === 0)
                <td rowspan="{{ count($validator) }}" class="status-cell">VALIDATOR</td>
                @endif
                <td>{{ $i + 1 }}</td>
                <td>{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
                <td>{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
                <td>
                    <div class="sig-block" style="text-align: center;">
                        @if($v['qr_code'] ?? null)
                            <img src="{{ $v['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                        @elseif($v['signature'] ?? null)
                            <img src="{{ $v['signature'] }}" class="sig-image" style="max-height: 30px; margin: 0 auto 5px auto;">
                        @endif
                        <div>{{ $v['date'] ?? '' }}</div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
