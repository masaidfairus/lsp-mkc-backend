{{-- resources/views/forms/IA/ia10.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.10 - Verifikasi Pihak Ketiga</title>
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

        .info-rahasia {
            text-align: right;
            font-style: italic;
            margin-bottom: 10px;
        }

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

        .unit-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
            page-break-inside: avoid;
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

        .data-pihak-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .data-pihak-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            word-wrap: break-word;
        }

        .pertanyaan-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .pertanyaan-table th, .pertanyaan-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .pertanyaan-table th {
            background: #e0e0e0;
            font-weight: bold;
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

        .footer-note {
            font-size: 8pt;
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 5px;
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
    $work_groups = $work_groups ?? [];
    $third_party = $third_party ?? [];
    $verification = $verification ?? [];
    $narrative = $narrative ?? [];
    $conclusion = $conclusion ?? [];
    $asesor_signature = $asesor_signature ?? null;
    $asesor_qr_code = $asesor_qr_code ?? null;
    $asesor_reg = $asesor_reg ?? '';
@endphp

<div class="judul">FR.IA.10. VPK – VERIFIKASI PIHAK KETIGA</div>

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

<div class="info-rahasia">Informasi ini bersifat Rahasia</div>

<div class="panduan-box" style="page-break-inside: avoid;">
    <div class="panduan-title">Panduan bagi Asesor</div>
    <ul style="padding-left:20px;">
        <li>Gunakan formulir ini untuk memverifikasi kompetensi asesi melalui pihak ketiga (pengawas atau atasan).</li>
    </ul>
</div>

<div style="font-weight:bold; margin-bottom:5px;">Unit Kompetensi :</div>
@foreach ($work_groups as $kp)
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
        @foreach ($kp['units'] ?? [] as $i => $u)
        <tr>
            @if ($i === 0)
            <td rowspan="{{ count($kp['units'] ?? [1]) }}">{{ $kp['group_name'] ?? '' }}</td>
            @endif
            <td style="text-align:center;">{{ $u['no'] ?? ($i + 1) }}</td>
            <td>{{ $u['unit_code'] ?? '' }}</td>
            <td>{{ $u['unit_title'] ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach

<div style="font-weight:bold; margin-bottom:5px;">Data Pihak Ketiga :</div>
<table class="data-pihak-table">
    <tr>
        <td style="width:30%">Nama Pengawas</td>
        <td>{{ $third_party['name'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Tempat Kerja</td>
        <td>{{ $third_party['workplace'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>{{ $third_party['address'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Telepon</td>
        <td>{{ $third_party['phone'] ?? '' }}</td>
    </tr>
</table>

<table class="pertanyaan-table">
    <thead>
        <tr>
            <th style="width:50%">Pertanyaan</th>
            <th style="width:25%; text-align:center;">Ya</th>
            <th style="width:25%; text-align:center;">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($verification as $p)
        <tr style="page-break-inside: avoid;">
            <td>{{ $p['question'] ?? '' }}</td>
            <td style="text-align:center;"><span style="font-family: DejaVu Sans, sans-serif;">{!! ($p['is_yes'] ?? false) ? '☑' : '☐' !!}</span></td>
            <td style="text-align:center;"><span style="font-family: DejaVu Sans, sans-serif;">{!! ($p['is_no'] ?? false) ? '☑' : '☐' !!}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="font-weight:bold; margin-bottom:5px;">Penilaian Naratif :</div>
<table class="tbl" style="margin-bottom: 20px;">
    <tr>
        <td style="width:40%">Apa hubungan Anda dengan asesi?</td>
        <td>{{ $narrative['relationship_with_assessee'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Berapa lama Anda bekerja dengan asesi?</td>
        <td>{{ $narrative['work_duration'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?</td>
        <td>{{ $narrative['proximity_in_assessed_area'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Apa pengalaman teknis dan/atau kualifikasi Anda di bidang yang dinilai?</td>
        <td>{{ $narrative['technical_experience_qualification'] ?? '' }}</td>
    </tr>
</table>

<div style="font-weight:bold; margin-bottom:5px;">Kesimpulan Keseluruhan :</div>
<table class="tbl" style="margin-bottom: 20px;">
    <tr>
        <td style="width:40%">Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?</td>
        <td>{{ $conclusion['is_consistent_with_standards'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi</td>
        <td>{{ $conclusion['further_training_needs'] ?? '' }}</td>
    </tr>
    <tr>
        <td>Ada komentar lain</td>
        <td>{{ $conclusion['other_comments'] ?? '' }}</td>
    </tr>
</table>

<table class="ttd-table">
    <tr><td colspan="2" class="ttd-header">Asesor :</td></tr>
    <tr>
        <td>
            Nama : {{ $nama_asesor }}<br>
            No. Reg : {{ $asesor_reg }}<br><br>
            Tanda tangan :<br>
            <div class="sig-block">
                @if($asesor_qr_code)
                    <img src="{{ $asesor_qr_code }}" class="sig-image" style="width:50px;height:50px;">
                @elseif($asesor_signature)
                    <img src="{{ $asesor_signature }}" class="sig-image" style="max-height:65px;">
                @endif
                <div>Tanggal : {{ $tanggal }}</div>
            </div>
        </td>
        <td>
            Tanggal :<br>
            {{ $tanggal }}
        </td>
    </tr>
</table>

<div class="footer-note">
    Catatan: Formulir ini harus dikirimkan langsung oleh pihak ketiga kepada LSP.
</div>

</body>
</html>
