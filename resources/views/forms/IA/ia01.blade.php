<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.01</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            padding: 4rem
        }
        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }
        .page-break { page-break-before: always; }

        .judul { font-size: 10pt; font-weight: bold; margin-bottom: 8px; }

        /* Tabel umum */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 8.5pt;
            line-height: 1.4;
        }

        /* Panduan box */
        .panduan-box {
            border: 1px solid #000;
            margin-bottom: 10px;
        }
        .panduan-title {
            font-weight: bold;
            font-size: 9pt;
            background: #e8e8e8;
            padding: 4px 8px;
            border-bottom: 1px solid #000;
        }
        .panduan-body { padding: 6px 10px; }
        .panduan-item {
            margin-bottom: 4px;
            padding-left: 14px;
            text-indent: -14px;
            font-size: 8pt;
            line-height: 1.4;
        }
        .panduan-item::before { content: "• "; }

        /* Tabel kelompok pekerjaan */
        .tbl-kp {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 1rem;
            font-size: 8.5pt;
        }
        .tbl-kp th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-kp td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* Header unit kompetensi */
        .tbl-unit-header {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
            font-size: 8.5pt;
        }
        .tbl-unit-header td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        /* Tabel KUK utama */
        .tbl-kuk {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .tbl-kuk th {
            border: 1px solid #000;
            padding: 4px 5px;
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            line-height: 1.3;
        }
        .tbl-kuk td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8pt;
            line-height: 1.4;
        }
        .tbl-kuk td.center { text-align: center; vertical-align: middle; }
        .tbl-kuk td.no { text-align: center; vertical-align: top; width: 20px; }

        .cb {
            display: inline-block;
            width: 9px; height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        /* Umpan balik */
        .umpan-box {
            border: 1px solid #000;
            padding: 5px 7px;
            min-height: 40px;
            margin-bottom: 10px;
            font-size: 8pt;
        }

        /* Rekomendasi + TTD */
        .tbl-footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8.5pt;
        }
        .tbl-footer td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            word-wrap: break-word;
            line-height: 1.5;
        }
        .ttd-row { height: 35px; }
    </style>
</head>
<body>

@php
    $nama_skema  = $nama_skema ?? '';
    $kode_skema  = $kode_skema ?? '';
    $tuk         = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi  = $nama_asesi ?? '';
    $tanggal     = $tanggal ?? '';
    $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
@endphp

{{-- JUDUL --}}
<div class="judul">FR.IA.01. CL – CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</div>

{{-- HEADER INFO --}}
<table class="tbl">
    <colgroup>
        <col style="width:28%;">
        <col style="width:14%;">
        <col style="width:3%;">
        <col style="width:55%;">
    </colgroup>
    <tr>
        <td rowspan="2" style="vertical-align:middle;" colspan="3">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
        <td>Judul</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="4">{{ $nama_skema }}</td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="4">{{ $kode_skema }}</td>
    </tr>
    <tr>
        <td colspan="4">TUK</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $tuk }}</td>
    </tr>
    <tr>
        <td colspan="4">Nama Asesor</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td colspan="4">Nama Asesi</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $nama_asesi }}</td>
    </tr>
    <tr>
        <td colspan="4">Tanggal</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $tanggal }}</td>
    </tr>
</table>

{{-- PANDUAN --}}
<div class="panduan-box">
    <div class="panduan-title">PANDUAN BAGI ASESOR</div>
    <div class="panduan-body">
        <div class="panduan-item">Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</div>
        <div class="panduan-item">Isilah standar industri atau tempat kerja.</div>
        <div class="panduan-item">Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</div>
        <div class="panduan-item">Isilah kolom KUK sesuai dengan Unit Kompetensi/SKKNI.</div>
    </div>
</div>

{{-- LOOP KELOMPOK PEKERJAAN --}}
@foreach($kelompok_pekerjaan as $kp)

    @if(!$loop->first)
    <div class="page-break"></div>
    @endif

    {{-- Tabel daftar unit --}}
    <table class="tbl-kp">
        <colgroup>
            <col style="width:18%;">
            <col style="width:5%;">
            <col style="width:20%;">
            <col style="width:57%;">
        </colgroup>
        <tr>
            <th colspan="2">Kelompok Pekerjaan {{ $kp['no'] }}</th>
            <th>No.</th>
            <th colspan="2">Kode Unit</th>
            <th colspan="3" style="text-align:left; padding-left:8px;">Judul Unit</th>
        </tr>
        @foreach($kp['unit'] as $i => $unit)
        <tr>
            @if($i === 0)
            <td colspan="2" rowspan="{{ count($kp['unit']) }}" style="vertical-align:middle; text-align:center; font-size:8pt;">{{ $kp['nama'] }}</td>
            @endif
            <td style="text-align:center;">{{ $i + 1 }}.</td>
            <td colspan="2">{{ $unit['kode'] }}</td>
            <td colspan="3">{{ $unit['judul'] }}</td>
        </tr>
        @endforeach
    </table>

    {{-- Per unit: header + tabel KUK --}}
    @foreach($kp['unit'] as $ui => $unit)

        @if($ui > 0)
        <div class="page-break"></div>
        @endif

        {{-- Header unit --}}
        <table class="tbl-unit-header" style="margin-bottom:0;">
            <colgroup>
                <col style="width:22%;">
                <col style="width:12%;">
                <col style="width:3%;">
                <col style="width:63%;">
            </colgroup>
            <tr>
                <td colspan="2"  rowspan="2" style="vertical-align:middle; font-weight:bold;">Unit Kompetensi {{ ($kp['unit_offset'] ?? 0) + $ui + 1 }}</td>
                <td>Kode Unit</td>
                <td style="text-align:center;">:</td>
                <td colspan="3">{{ $unit['kode'] }}</td>
            </tr>
            <tr>
                <td>Judul Unit</td>
                <td style="text-align:center;">:</td>
                <td colspan="3">{{ $unit['judul'] }}</td>
            </tr>
        </table>

        {{-- Tabel KUK --}}
        <table class="tbl-kuk">
            <colgroup>
                <col style="width:20px;">
                <col style="width:18%;">
                <col style="width:35%;">
                <col style="width:17%;">
                <col style="width:6%;">
                <col style="width:6%;">
                <col style="width:10%;">
            </colgroup>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" colspan="2">Elemen</th>
                <th rowspan="2" colspan="2">Kriteria Unjuk Kerja</th>
                <th rowspan="2">Standar Industri atau Tempat Kerja</th>
                <th colspan="2">Pencapaian</th>
                <th rowspan="2">Penilaian Lanjut</th>
            </tr>
            <tr>
                <th>Ya</th>
                <th>Tidak</th>
            </tr>
             @php $elNo = 0; @endphp
            @foreach($unit['elemen'] as $el)
                @php $elNo++; $kukCount = count($el['kuk']); @endphp
                @foreach($el['kuk'] as $ki => $kuk)
                @php $isLast = ($ki === $kukCount - 1); @endphp
                <tr>
                    <td class="no" style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                        @if($ki === 0){{ $elNo }}.@endif
                    </td>
                    <td colspan="2" style="vertical-align:top; border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                        @if($ki === 0){{ $el['nama'] }}@endif
                    </td>
                    <td colspan="2">{{ $kuk }}</td>
                    <td></td>
                    <td class="center" style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                        @if($ki === 0)<span class="cb"></span>@endif
                    </td>
                    <td class="center" style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                        @if($ki === 0)<span class="cb"></span>@endif
                    </td>
                    <td style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};"></td>
                </tr>
                @endforeach
            @endforeach
        </table>

        {{-- Umpan balik --}}
        <div class="umpan-box">Umpan balik bagi Asesi:</div>

    @endforeach

@endforeach

{{-- REKOMENDASI + TTD --}}
<div class="page-break"></div>
<table class="tbl-footer">
    <colgroup>
        <col style="width:48%;">
        <col style="width:52%;">
    </colgroup>
    <tr>
        <td style="vertical-align:top;">
            <div style="font-weight:bold; margin-bottom:6px;">Rekomendasi:</div>
            <div style="margin-bottom:6px;"><span class="cb"></span> &nbsp; Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>KOMPETEN</strong></div>
            <div style="margin-bottom:10px;"><span class="cb"></span> &nbsp; Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>BELUM KOMPETEN</strong></div>
            <div style="font-size:8pt; line-height:1.8;">
                pada:<br>
                Kelompok Pekerjaan: ...<br>
                Unit: .....<br>
                Elemen: ......<br>
                KUK: .......
            </div>
        </td>
        <td style="vertical-align:top; padding:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td colspan="3" style="font-weight:bold; padding:4px 7px; border-bottom:1px solid #000;">Asesi</td>
                </tr>
                <tr>
                    <td style="padding:4px 7px; width:40%; border-right:1px solid #000; border-bottom:1px solid #000;">Nama</td>
                    <td style="padding:4px 5px; width:4%; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="padding:4px 7px; border-bottom:1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="padding:4px 7px; height:35px; vertical-align:top; border-right:1px solid #000; border-bottom:1px solid #000;">Tanda tangan / Tanggal</td>
                    <td style="padding:4px 5px; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="border-bottom:1px solid #000;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold; padding:4px 7px; border-bottom:1px solid #000;">Asesor</td>
                </tr>
                <tr>
                    <td style="padding:4px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">Nama</td>
                    <td style="padding:4px 5px; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="border-bottom:1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="padding:4px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">No. Reg</td>
                    <td style="padding:4px 5px; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="border-bottom:1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="padding:4px 7px; height:35px; vertical-align:top; border-right:1px solid #000;">Tanda tangan / Tanggal</td>
                    <td style="padding:4px 5px; text-align:center; border-right:1px solid #000;">:</td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>