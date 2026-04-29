{{-- resources/views/forms/IA/ia07.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.07 - Daftar Pertanyaan Lisan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
            line-height: 1.35;
        }
        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }
        .judul {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-align: start;
        }
        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 12px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .tbl th {
            border: 1px solid #000;
            padding: 5px 7px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            word-wrap: break-word;
        }
        .header-val { font-weight: bold; }

        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 15px;
            word-wrap: break-word;
        }
        .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .panduan-box ul {
            padding-left: 20px;
        }
        .panduan-box li {
            margin-bottom: 4px;
        }

        .unit-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .unit-table th, .unit-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .unit-table th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .pertanyaan-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .pertanyaan-table th, .pertanyaan-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .pertanyaan-table th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }
        .no-col { width: 5%; }
        .pertanyaan-col { width: 35%; }
        .kunci-col { width: 25%; }
        .jawaban-col { width: 25%; }
        .pencapaian-col { width: 10%; text-align: center; }

        .umpan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            min-height: 80px;
            margin-bottom: 20px;
            word-wrap: break-word;
            page-break-inside: avoid;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .ttd-header {
            font-weight: bold;
            background: #e8e8e8;
        }
        .ttd-space { height: 70px; }

        .pv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            text-align: center;
            word-wrap: break-word;
            vertical-align: middle;
        }
        .pv-table th {
            background: #e0e0e0;
            font-weight: bold;
        }
                .page-break { page-break-before: always; }
    </style>
</head>
<body>

@php
    $nama_skema = $nama_skema ?? '';
    $kode_skema = $kode_skema ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
    $pertanyaan = $pertanyaan ?? [];
    $asesi_signature = $asesi_signature ?? null;
    $asesor_signature = $asesor_signature ?? null;
    $asesor_qr_code = $asesor_qr_code ?? null;
    $asesor_reg = $asesor_reg ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="judul">FR.IA.07. PERTANYAAN LISAN</div>

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
</table>

<table class="tbl" style="margin-bottom: 20px;">
    <tr>
        <td style="width: 20%; font-weight: bold; background: #f0f0f0; vertical-align: top;">Instruksi:</td>
        <td>
            <ol style="margin-left: 20px; padding: 5px;">
                <li>Ajukan pertanyaan kepada Asesi dari daftar pertanyaan di bawah ini untuk mengonfirmasi pengetahuan, sebagaimana diperlukan.</li>
                <li>Tempatkan centang di kotak pencapaian "Ya" atau "Tidak".</li>
                <li>Tulis jawaban Asesi secara singkat di tempat yang disediakan dan konfirmasi ulang untuk setiap jawaban.</li>
            </ol>
        </td>
    </tr>
</table>

<div style="page-break-inside: avoid;">
<div style="font-weight:bold; margin-bottom:5px;">Unit Kompetensi :</div>
@if(count($kelompok_pekerjaan) > 0)
    @foreach ($kelompok_pekerjaan as $kp)
    <table class="unit-table">
        <thead>
            <tr>
                <th style="width:25%">Kelompok Pekerjaan</th>
                <th style="width:8%">No.</th>
                <th style="width:20%">Kode Unit</th>
                <th>Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kp['units'] as $i => $u)
            <tr>
                @if ($i === 0)
                <td rowspan="{{ count($kp['units']) }}">{{ $kp['group_name'] ?? '' }}</td>
                @endif
                <td style="text-align:center;">{{ !empty($u['no']) ? $u['no'] : ($i + 1) }}</td>
                <td>{{ $u['unit_code'] ?? '' }}</td>
                <td>{{ $u['unit_title'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
@else
    <table class="unit-table">
        <thead>
            <tr>
                <th style="width:25%">Kelompok Pekerjaan</th>
                <th style="width:8%">No.</th>
                <th style="width:20%">Kode Unit</th>
                <th>Judul Unit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="text-align:center; height: 50px;">Data Unit Kompetensi Belum Diisi</td>
            </tr>
        </tbody>
    </table>
@endif
</div>

    <div class="page-break"></div>
<table class="pertanyaan-table">
    <thead>
        <tr>
            <th rowspan="2" style="width: 6%;">No.</th>
            <th rowspan="2">Pertanyaan</th>
            <th colspan="2" style="width: 18%;">Pencapaian</th>
        </tr>
        <tr>
            <th style="width: 9%;">Ya</th>
            <th style="width: 9%;">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @if(count($pertanyaan) > 0)
            @foreach ($pertanyaan as $i => $p)
            <tr>
                <td style="text-align:center; vertical-align: top; padding-top: 10px; border-bottom: 2px solid #000;">{{ $i + 1 }}.</td>
                <td style="padding: 0; border-bottom: 2px solid #000;">
                    <div style="padding: 8px; border-bottom: 1px solid #000; background: #f9f9f9;">
                        <strong>Pertanyaan:</strong><br>
                        {{ $p['pertanyaan'] }}
                    </div>
                    <div style="padding: 8px; border-bottom: 1px solid #000;">
                        <strong>Kunci Jawaban:</strong><br>
                        {{ $p['kunci_jawaban'] }}
                    </div>
                    <div style="padding: 8px;">
                        <strong>Jawaban Asesi:</strong><br>
                        {{ $p['jawaban_asesi'] ?: '...' }}
                    </div>
                </td>
                <td style="text-align:center; vertical-align: middle; font-family: DejaVu Sans, sans-serif; border-bottom: 2px solid #000;">
                    {!! ($p['is_yes'] ?? false) ? '&#9745;' : '&#9744;' !!}
                </td>
                <td style="text-align:center; vertical-align: middle; font-family: DejaVu Sans, sans-serif; border-bottom: 2px solid #000;">
                    {!! ($p['is_no'] ?? false) ? '&#9745;' : '&#9744;' !!}
                </td>
            </tr>
            @endforeach
        @else
            @for($i=1; $i<=2; $i++)
            <tr>
                <td style="text-align:center; vertical-align: top; padding-top: 10px;">{{ $i }}.</td>
                <td style="padding: 0;">
                    <div style="padding: 8px; border-bottom: 1px solid #000; background: #f9f9f9;"><strong>Pertanyaan:</strong></div>
                    <div style="padding: 8px; border-bottom: 1px solid #000;"><strong>Kunci Jawaban:</strong></div>
                    <div style="padding: 8px; min-height: 40px;"><strong>Jawaban Asesi:</strong></div>
                </td>
                <td style="text-align:center; vertical-align: middle; font-family: DejaVu Sans, sans-serif;">&#9744;</td>
                <td style="text-align:center; vertical-align: middle; font-family: DejaVu Sans, sans-serif;">&#9744;</td>
            </tr>
            @endfor
        @endif
    </tbody>
</table>

<div class="umpan-box">
    <strong>Umpan Balik :</strong><br>
    {{ $umpan_balik ?: '...' }}
    <br><br>
    <strong>Aspek pengetahuan seluruh unit pada kelompok pekerjaan yang diujikan:</strong> {{ ($aspek_pengetahuan ?? '') == 'tercapai' ? 'Tercapai' : 'Belum Tercapai' }}
    <br><br>
    <strong>Unit / Elemen / KUK yang belum tercapai:</strong><br>
    {{ $detail_belum_tercapai ?: '-' }}
</div>

<div style="page-break-inside: avoid;">
    <table class="ttd-table" style="border: 1px solid #000;">
        <tr>
            <td colspan="3" style="font-weight:bold; border:1px solid #000; padding:8px; background:#f0f0f0;">Asesi :</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px; width: 30%;">Nama</td>
            <td style="text-align:center; border:1px solid #000; padding:8px; width: 3%;">:</td>
            <td style="border:1px solid #000; padding:8px;">{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px;">Tanda tangan/Tanggal</td>
            <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
            <td style="border:1px solid #000; padding:8px;">
                <div class="sig-block">
                    @if($asesi_signature)
                        <img src="{{ $asesi_signature }}" style="max-height: 50px;">
                    @endif
                    <div style="font-size: 8.5pt;">{{ $tanggal }}</div>
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
                        <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px;">
                    @elseif($asesor_signature)
                        <img src="{{ $asesor_signature }}" style="max-height: 50px;">
                    @endif
                    <div style="font-size: 8.5pt;">{{ $tanggal }}</div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div style="page-break-inside: avoid;">
    <div style="font-weight:bold; margin:20px 0 8px 0; text-transform: uppercase;">PENYUSUN DAN VALIDATOR</div>
    <table class="pv-table">
        <colgroup>
            <col style="width:18%;">
            <col style="width:7%;">
            <col style="width:35%;">
            <col style="width:20%;">
            <col style="width:20%;">
        </colgroup>
        <thead>
            <tr>
                <th colspan="2">STATUS</th>
                <th>NO</th>
                <th>NAMA</th>
                <th>NOMOR MET</th>
                <th>TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['Penyusun' => $compilers ?? $penyusun ?? [], 'Validator' => $validators ?? $validator ?? []] as $status => $items)
                @php 
                    $items = (array)$items;
                    $count = max(2, count($items)); 
                @endphp
                @for($i = 0; $i < $count; $i++)
                    @php
                        $item = $items[$i] ?? [];
                        $name = $item['name'] ?? $item['nama'] ?? '';
                        $reg = $item['reg_number'] ?? $item['nomor_met'] ?? '';
                        $qr = $item['qr_code'] ?? null;
                        $sig = $item['signature'] ?? null;
                        $date = $item['date'] ?? '';
                    @endphp
                    <tr>
                        @if($i === 0)
                            <td colspan="2" rowspan="{{ $count }}" style="vertical-align:middle; font-weight:bold; text-transform:uppercase;">{{ $status }}</td>
                        @endif
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $reg }}</td>
                        <td style="height:55px; vertical-align: middle;">
                            <div style="text-align:center;">
                                @if($qr)
                                    <img src="{{ $qr }}" style="width:45px;height:45px;display:inline-block;">
                                @elseif($sig)
                                    <img src="{{ $sig }}" style="max-height:40px;display:inline-block;">
                                @endif
                                @if($date)
                                    <div style="font-size:8pt; margin-top:2px;">{{ $date }}</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>