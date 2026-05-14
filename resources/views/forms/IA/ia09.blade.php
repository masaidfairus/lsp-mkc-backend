{{-- resources/views/forms/IA/ia09.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.09 - Pertanyaan Wawancara</title>
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

        .bukti-porto-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
        }
        .bukti-porto-table th, .bukti-porto-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            text-align: center;
            word-wrap: break-word;
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
        .pertanyaan-col { width: 55%; }
        .kesimpulan-col { width: 25%; }
        .pencapaian-col { width: 15%; text-align: center; }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
            page-break-inside: avoid;
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
        .ttd-space { height: 65px; }

        .pv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: avoid;
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
    $bukti_portofolio = $bukti_portofolio ?? [];
    $pertanyaan_wawancara = $pertanyaan_wawancara ?? [];
    $asesi_signature = $asesi_signature ?? null;
    $asesor_signature = $asesor_signature ?? null;
    $asesor_qr_code = $asesor_qr_code ?? null;
    $asesor_reg = $asesor_reg ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="judul">FR.IA.09. PW - PERTANYAAN WAWANCARA</div>

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

<div class="panduan-box">
    <div class="panduan-title">Panduan bagi Asesor</div>
    <ul style="padding-left:20px;">
        <li>Review portofolio asesi dan ajukan pertanyaan wawancara untuk mengonfirmasi kompetensi asesi.</li>
    </ul>
</div>

<div style="font-weight:bold; margin-bottom:5px;">Bukti-bukti Portofolio :</div>
<table class="bukti-porto-table">
    <thead>
        <tr>
            <th style="width:8%">No.</th>
            <th>Nama Bukti Portofolio</th>
        </tr>
    </thead>
    <tbody>
        @if(count($bukti_portofolio) > 0)
            @foreach ($bukti_portofolio as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left;">{{ $b['nama_bukti'] }}</td>
            </tr>
            @endforeach
        @else
            @for($i=1; $i<=2; $i++)
            <tr>
                <td>{{ $i }}</td>
                <td style="height:35px;"></td>
            </tr>
            @endfor
        @endif
    </tbody>
</table>

<table class="pertanyaan-table">
    <thead>
        <tr>
            <th class="no-col" rowspan="2">No.</th>
            <th class="pertanyaan-col" rowspan="2">Pertanyaan Wawancara</th>
            <th class="kesimpulan-col" rowspan="2">Kesimpulan Jawaban Asesi</th>
            <th class="pencapaian-col" colspan="2">Pencapaian</th>
        </tr>
        <tr>
            <th style="width:7.5%; font-size:10px;">Ya</th>
            <th style="width:7.5%; font-size:10px;">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pertanyaan_wawancara as $i => $p)
        <tr>
            <td style="text-align:center;">{{ $i + 1 }}</td>
            <td>{{ $p['pertanyaan'] }}</td>
            <td>{{ $p['kesimpulan_jawaban'] }}</td>
            <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">
                {!! ($p['is_k'] ?? false) ? '&#9745;' : '&#9744;' !!}
            </td>
            <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">
                {!! ($p['is_bk'] ?? false) ? '&#9745;' : '&#9744;' !!}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="ttd-table">
    <tr><td colspan="3" class="ttd-header">Asesi :</td></tr>
    <tr>
        <td style="width:25%">Nama</td>
        <td style="width:3%;text-align:center">:</td>
        <td>{{ $nama_asesi }}</td>
    </tr>
    <tr style="height:70px">
        <td>Tanda tangan & Tanggal</td>
        <td style="text-align:center">:</td>
        <td>
            @if($asesi_signature)
                <img src="{{ $asesi_signature }}" style="max-height:65px;"><br>
            @endif
            {{ $tanggal }}
        </td>
    </tr>
    <tr><td colspan="3" class="ttd-header">Asesor :</td></tr>
    <tr>
        <td>Nama</td>
        <td style="text-align:center">:</td>
        <td>{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td>No. Reg</td>
        <td style="text-align:center">:</td>
        <td>{{ $asesor_reg }}</td>
    </tr>
    <tr style="height:70px">
        <td>Tanda tangan & Tanggal</td>
        <td style="text-align:center">:</td>
        <td>
            @if($asesor_qr_code)
                <img src="{{ $asesor_qr_code }}" style="width:50px;height:50px;"><br>
            @elseif($asesor_signature)
                <img src="{{ $asesor_signature }}" style="max-height:65px;"><br>
            @endif
            {{ $tanggal }}
        </td>
    </tr>
</table>

<div style="page-break-inside: avoid;">
<div style="font-weight:bold; margin-bottom:5px;">PENYUSUN DAN VALIDATOR</div>
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
            <td rowspan="{{ count($penyusun) }}" style="font-weight:bold;">PENYUSUN</td>
            @endif
            <td>{{ $i + 1 }}</td>
            <td>{{ $p['name'] ?? $p['nama'] ?? '' }}</td>
            <td>{{ $p['reg_number'] ?? $p['nomor_met'] ?? '' }}</td>
            <td>
                @if($p['qr_code'] ?? null)
                    <img src="{{ $p['qr_code'] }}" style="width: 45px; height: 45px;"><br>
                @elseif($p['signature'] ?? null)
                    <img src="{{ $p['signature'] }}" style="max-height: 35px;"><br>
                @endif
                {{ $p['date'] ?? '' }}
            </td>
        </tr>
        @endforeach
        @foreach ($validator as $i => $v)
        <tr>
            @if ($i === 0)
            <td rowspan="{{ count($validator) }}" style="font-weight:bold;">VALIDATOR</td>
            @endif
            <td>{{ $i + 1 }}</td>
            <td>{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
            <td>{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
            <td>
                @if($v['qr_code'] ?? null)
                    <img src="{{ $v['qr_code'] }}" style="width: 45px; height: 45px;"><br>
                @elseif($v['signature'] ?? null)
                    <img src="{{ $v['signature'] }}" style="max-height: 35px;"><br>
                @endif
                {{ $v['date'] ?? '' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

</body>
</html>
