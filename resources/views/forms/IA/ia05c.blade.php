{{-- resources/views/forms/IA/ia05c.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.05C - Lembar Jawaban Pilihan Ganda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
            line-height: 1.4;
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

        .section-label {
            font-weight: normal;
            font-size: 9.5pt;
            margin-bottom: 5px;
        }

        /* Jawaban table - Side by side */
        .jawaban-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .jawaban-table {
            width: 48%;
            border-collapse: collapse;
            table-layout: fixed;
            float: left;
            margin-right: 2%;
            margin-bottom: 10px;
        }
        .jawaban-table:last-child {
            margin-right: 0;
        }
        .jawaban-table th, .jawaban-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            text-align: center;
            word-wrap: break-word;
            font-size: 9pt;
        }
        .jawaban-table th {
            background-color: #fff;
            font-weight: bold;
        }
        .col-no { width: 15%; }
        .col-jawab { width: 45%; }
        .col-pen { width: 20%; }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Signatures */
        .sig-block {
            text-align: left;
            margin-top: 5px;
        }
        .sig-image {
            display: block;
            margin-bottom: 5px;
        }

        /* PV Table */
        .pv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="judul">FR.IA.05.C. LEMBAR JAWABAN PILIHAN GANDA</div>

<table class="tbl">
        <colgroup>
            <col style="width:28%;">
            <col style="width:14%;">
            <col style="width:3%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td>Judul :</td>
            <td style="font-weight:bold;" colspan="5">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor :</td>
            <td style="font-weight:bold;" colspan="5">{{ $kode_skema }}</td>
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
        <tr>
            <td colspan="3">Waktu :</td>
            <td colspan="5">{{ $waktu ?? '-' }}</td>
        </tr>
    </table>

<div class="section-label">Lembar Jawaban Pertanyaan Tertulis – Pilihan Ganda:</div>

@php
    $jawaban_data = $jawaban_data ?? [];
    $total = count($jawaban_data);
    $half = (int) ceil($total / 2);
    $kolom1 = array_slice($jawaban_data, 0, $half);
    $kolom2 = array_slice($jawaban_data, $half);
@endphp

<div class="jawaban-container clearfix">
    {{-- Tabel Sebelah Kiri --}}
    <table class="jawaban-table">
        <thead>
            <tr>
                <th rowspan="2" class="col-no">No.</th>
                <th rowspan="2" class="col-jawab">Jawaban</th>
                <th colspan="2">Pencapaian</th>
            </tr>
            <tr>
                <th class="col-pen">Ya</th>
                <th class="col-pen">Tidak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kolom1 as $item)
            <tr>
                <td>{{ !empty($item['no']) ? $item['no'] : ($loop->iteration) }}.</td>
                <td style="font-weight:bold;">{{ strtoupper($item['jawaban'] ?? '') }}</td>
                <td><span style="font-family: DejaVu Sans, sans-serif;">{!! ($item['is_correct'] ?? false) ? '☑' : '☐' !!}</span></td>
                <td><span style="font-family: DejaVu Sans, sans-serif;">{!! !($item['is_correct'] ?? false) ? '☑' : '☐' !!}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Sebelah Kanan --}}
    @if(count($kolom2) > 0)
    <table class="jawaban-table">
        <thead>
            <tr>
                <th rowspan="2" class="col-no">No.</th>
                <th rowspan="2" class="col-jawab">Jawaban</th>
                <th colspan="2">Pencapaian</th>
            </tr>
            <tr>
                <th class="col-pen">Ya</th>
                <th class="col-pen">Tidak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kolom2 as $item)
            <tr>
                <td>{{ !empty($item['no']) ? $item['no'] : ($loop->iteration) }}.</td>
                <td style="font-weight:bold;">{{ strtoupper($item['jawaban'] ?? '') }}</td>
                <td><span style="font-family: DejaVu Sans, sans-serif;">{!! ($item['is_correct'] ?? false) ? '☑' : '☐' !!}</span></td>
                <td><span style="font-family: DejaVu Sans, sans-serif;">{!! !($item['is_correct'] ?? false) ? '☑' : '☐' !!}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<table class="tbl" style="margin-top: 10px; border-collapse: collapse; page-break-inside: avoid;">
    <tr>
        <td style="width:35%; font-weight:bold; border:1px solid #000; padding:8px;">Umpan balik untuk asesi</td>
        <td style="width:3%; text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">
            Aspek pengetahuan seluruh unit pada kelompok pekerjaan yang diujikan <strong>{{ ($knowledge_status ?? '') === 'Tercapai' ? 'Tercapai' : 'Belum Tercapai' }}</strong><br><br>
            Tuliskan unit/elemen/KUK jika belum tercapai:<br>
            {!! nl2br(e($unachieved_details ?? '')) !!}<br><br>
            Catatan lain:<br>
            {!! nl2br(e($umpan_balik ?? '')) !!}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight:bold; border:1px solid #000; padding:8px; background:#f0f0f0;">Asesi :</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:8px;">Nama</td>
        <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">{{ $nama_asesi }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:8px;">Tanda tangan/Tanggal</td>
        <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">
            <div class="sig-block">
                @if($asesi_signature)
                    <img src="{{ $asesi_signature }}" class="sig-image" style="max-height: 50px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-weight:bold; border:1px solid #000; padding:8px; background:#f0f0f0;">Asesor :</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:8px;">Nama</td>
        <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:8px;">No. Reg</td>
        <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">{{ $asesor_reg ?? '' }}</td>
    </tr>
    <tr>
        <td style="border:1px solid #000; padding:8px;">Tanda tangan/Tanggal</td>
        <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
        <td style="border:1px solid #000; padding:8px;">
            <div class="sig-block">
                @if($asesor_qr_code)
                    <img src="{{ $asesor_qr_code }}" class="sig-image" style="width: 50px; height: 50px;">
                @elseif($asesor_signature)
                    <img src="{{ $asesor_signature }}" class="sig-image" style="max-height: 50px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
        </td>
    </tr>
</table>

<div style="page-break-inside: avoid; margin-top: 15px;">
    <div style="font-weight:bold; font-size:10pt; margin-bottom: 5px;">PENYUSUN DAN VALIDATOR</div>
    <table class="pv-table" style="width:100%; border-collapse:collapse; border: 1px solid #000;">
        <thead>
            <tr>
                <th style="width:15%; border:1px solid #000; padding:4px; background:#e0e0e0;">STATUS</th>
                <th style="width:8%; border:1px solid #000; padding:4px; background:#e0e0e0;">NO</th>
                <th style="width:35%; border:1px solid #000; padding:4px; background:#e0e0e0;">NAMA</th>
                <th style="width:20%; border:1px solid #000; padding:4px; background:#e0e0e0;">NOMOR MET</th>
                <th style="width:22%; border:1px solid #000; padding:4px; background:#e0e0e0;">TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penyusun as $i => $p)
            <tr>
                @if ($i === 0)
                <td rowspan="{{ count($penyusun) }}" style="border:1px solid #000; padding:4px; text-align:center; font-weight:bold;">PENYUSUN</td>
                @endif
                <td style="border:1px solid #000; padding:4px; text-align:center;">{{ $i + 1 }}</td>
                <td style="border:1px solid #000; padding:4px;">{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
                <td style="border:1px solid #000; padding:4px; text-align:center;">{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
                <td style="border:1px solid #000; padding:4px;">
                    <div class="sig-block" style="text-align: center;">
                        @if($p['qr_code'] ?? null)
                            <img src="{{ $p['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                        @elseif($p['signature'] ?? null)
                            <img src="{{ $p['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                        @endif
                        <div style="font-size:8pt;">{{ $p['date'] ?? '' }}</div>
                    </div>
                </td>
            </tr>
            @endforeach
            @foreach ($validator as $i => $v)
            <tr>
                @if ($i === 0)
                <td rowspan="{{ count($validator) }}" style="border:1px solid #000; padding:4px; text-align:center; font-weight:bold;">VALIDATOR</td>
                @endif
                <td style="border:1px solid #000; padding:4px; text-align:center;">{{ $i + 1 }}</td>
                <td style="border:1px solid #000; padding:4px;">{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
                <td style="border:1px solid #000; padding:4px; text-align:center;">{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
                <td style="border:1px solid #000; padding:4px;">
                    <div class="sig-block" style="text-align: center;">
                        @if($v['qr_code'] ?? null)
                            <img src="{{ $v['qr_code'] }}" class="sig-image" style="width: 40px; height: 40px; margin: 0 auto 5px auto;">
                        @elseif($v['signature'] ?? null)
                            <img src="{{ $v['signature'] }}" class="sig-image" style="max-height: 35px; margin: 0 auto 5px auto;">
                        @endif
                        <div style="font-size:8pt;">{{ $v['date'] ?? '' }}</div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>