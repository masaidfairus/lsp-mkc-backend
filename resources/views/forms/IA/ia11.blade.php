{{-- resources/views/forms/IA/ia11.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.11 - Ceklis Reviu Produk</title>
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

        .produk-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .produk-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .produk-table .label { width: 35%; font-weight: bold; background: #f0f0f0; }

        .review-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
        }
        .review-table th, .review-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .review-table th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .rekomen-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 15px;
            word-wrap: break-word;
        }

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
    $nama_skema = $nama_skema ?? '';
    $kode_skema = $kode_skema ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $product_details = $product_details ?? [];
    $product_reviews = $product_reviews ?? [];
    $assessment_notes = $assessment_notes ?? '';
    $recommendation = $recommendation ?? [];
    $asesi_signature = $asesi_signature ?? null;
    $asesor_signature = $asesor_signature ?? null;
    $asesor_qr_code = $asesor_qr_code ?? null;
    $asesor_reg = $asesor_reg ?? '';
    $penyusun = $compilers ?? [];
    $validator = $validators ?? [];
@endphp

<div class="judul">FR.IA.11. CRP. CEKLIS REVIU PRODUK</div>

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
        <li>Gunakan formulir ini untuk mereviu produk yang dihasilkan asesi sebagai bukti kompetensi.</li>
    </ul>
</div>

<div style="font-weight:bold; margin-bottom:5px;">Rancangan Produk atau Data Teknis :</div>
<table class="produk-table">
    <tr>
        <td class="label">Nama produk yang dihasilkan</td>
        <td>{{ $product_details['product_name'] ?? '' }}</td>
    </tr>
    <tr>
        <td class="label">Standar Industri atau tempat kerja</td>
        <td>{{ $product_details['industry_standard'] ?? '' }}</td>
    </tr>
    <tr>
        <td class="label">Spesifikasi produk secara umum</td>
        <td>
            Dimensi: {{ $product_details['general_specifications']['dimensions'] ?? '' }}<br>
            Bahan: {{ $product_details['general_specifications']['material'] ?? '' }}<br>
            Berat: {{ $product_details['general_specifications']['weight'] ?? '' }}
        </td>
    </tr>
    <tr>
        <td class="label">Spesifikasi produk secara teknis</td>
        <td>{{ $product_details['technical_specifications'] ?? '' }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal pengoperasian / penggunaan</td>
        <td>{{ $product_details['usage_date'] ?? '' }}</td>
    </tr>
</table>

<div style="font-weight:bold; margin-bottom:5px;">Gambar produk (jika ada) :</div>
<div style="border: 1px solid #000; min-height: 100px; padding: 10px; text-align: center; margin-bottom: 20px;">
    @if(!empty($product_details['product_image_url']))
        <img src="{{ $product_details['product_image_url'] }}" style="max-width: 100%; max-height: 400px; object-fit: contain;">
    @else
        <div style="color: #999; padding-top: 40px;">(Tidak ada gambar yang diunggah)</div>
    @endif
</div>

@foreach ($product_reviews as $idx => $category)
<div style="font-weight:bold; margin: 10px 0 5px 0;">{{ ($idx + 1) }}. {{ $category['category'] ?? '' }}</div>
<table class="review-table">
    <thead>
        <tr>
            <th style="width:65%">Kriteria Reviu</th>
            <th style="width:17.5%">Ya</th>
            <th style="width:17.5%">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($category['criteria'] ?? [] as $c)
        <tr>
            <td>{{ $c['criterion'] ?? '' }}</td>
            <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">{!! ($c['is_yes'] ?? false) ? '&#9745;' : '&#9744;' !!}</td>
            <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">{!! ($c['is_no'] ?? false) ? '&#9745;' : '&#9744;' !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach

<div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px;">
    <strong>Catatan :</strong><br><br>
    {{ $assessment_notes ?: '...' }}
</div>

<div class="rekomen-box">
    <strong>Rekomendasi Asesor :</strong><br><br>
    Keputusan :<br>
    <div style="margin: 5px 0;">
        <span style="font-family: DejaVu Sans, sans-serif; font-size:13pt;">{!! ($recommendation['is_competent'] ?? false) ? '&#9745;' : '&#9744;' !!}</span> Asesi telah memenuhi kriteria reviu produk, direkomendasikan <strong>KOMPETEN</strong>
    </div>
    <div style="margin: 5px 0;">
        <span style="font-family: DejaVu Sans, sans-serif; font-size:13pt;">{!! ($recommendation['is_not_competent'] ?? false) ? '&#9745;' : '&#9744;' !!}</span> Asesi belum memenuhi kriteria reviu produk, direkomendasikan <strong>OBSERVASI LANGSUNG</strong> pada:<br>
        @if($recommendation['is_not_competent'] ?? false)
            <div style="margin-left: 25px; margin-top: 5px;">
                Kelompok Pekerjaan: {{ $recommendation['observation_details']['work_group'] ?? '...' }}<br>
                Unit: {{ $recommendation['observation_details']['unit'] ?? '...' }}<br>
                Elemen: {{ $recommendation['observation_details']['element'] ?? '...' }}<br>
                KUK: {{ $recommendation['observation_details']['kuk'] ?? '...' }}
            </div>
        @else
            <div style="margin-left: 25px; margin-top: 5px; color: #666;">
                Kelompok Pekerjaan: ...<br>
                Unit: ...<br>
                Elemen: ...<br>
                KUK: ...
            </div>
        @endif
    </div>
</div>

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
            <div class="sig-block">
                @if($asesi_signature)
                    <img src="{{ $asesi_signature }}" class="sig-image" style="max-height:65px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
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
            <div class="sig-block">
                @if($asesor_qr_code)
                    <img src="{{ $asesor_qr_code }}" class="sig-image" style="width:60px;height:60px;">
                @elseif($asesor_signature)
                    <img src="{{ $asesor_signature }}" class="sig-image" style="max-height:65px;">
                @endif
                <div>{{ $tanggal }}</div>
            </div>
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
                <div class="sig-block" style="text-align: center;">
                    @if($p['qr_code'] ?? null)
                        <img src="{{ $p['qr_code'] }}" class="sig-image" style="width: 45px; height: 45px; margin: 0 auto 5px auto;">
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
            <td rowspan="{{ count($validator) }}" style="font-weight:bold;">VALIDATOR</td>
            @endif
            <td>{{ $i + 1 }}</td>
            <td>{{ $v['name'] ?? $v['nama'] ?? '' }}</td>
            <td>{{ $v['reg_number'] ?? $v['nomor_met'] ?? '' }}</td>
            <td>
                <div class="sig-block" style="text-align: center;">
                    @if($v['qr_code'] ?? null)
                        <img src="{{ $v['qr_code'] }}" class="sig-image" style="width: 45px; height: 45px; margin: 0 auto 5px auto;">
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

</body>
</html>