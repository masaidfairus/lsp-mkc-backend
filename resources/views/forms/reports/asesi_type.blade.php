<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Permohonan Sertifikasi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 40px;
        }

        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
            size: A4 portrait;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .logo-cell {
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .lsp-info {
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }
        .lsp-name {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .lsp-sub {
            font-size: 8.5pt;
            margin-top: 2px;
        }
        .lsp-alamat {
            font-size: 7.5pt;
            margin-top: 3px;
            color: #333;
        }
        .divider {
            border: none;
            border-top: 2.5px solid #000;
            margin-bottom: 2px;
        }
        .divider-thin {
            border: none;
            border-top: 1px solid #000;
            margin-bottom: 15px;
        }

        /* TITLE */
        .report-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .report-meta {
            text-align: center;
            font-size: 9pt;
            color: #444;
            margin-bottom: 20px;
        }

        /* FILTER & SUMMARY BOX */
        .summary-container {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .summary-table td {
            padding: 6px 10px;
            border: 1px solid #ccc;
            vertical-align: top;
        }
        .summary-header {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
        }
        .summary-label {
            font-weight: bold;
            width: 30%;
        }
        .summary-value {
            width: 70%;
        }

        /* DATA TABLE */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 25px;
        }
        .tbl th, .tbl td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .tbl th {
            background-color: #e6e6e6;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .tbl td.center { text-align: center; }
        .tbl td.right  { text-align: right; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pribadi {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }
        .badge-institusi {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-proses {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .badge-permohonan {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* SIGNATURE */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 9.5pt;
        }
        .footer-table td {
            vertical-align: top;
            width: 50%;
        }
        .signature-box {
            text-align: center;
            float: right;
            width: 250px;
        }
        .sig-space {
            height: 65px;
        }
    </style>
</head>
<body>

{{-- LSP HEADER --}}
<table class="header-table">
    <tr>
        <td style="width:12%; text-align:left;">
            @if(file_exists(public_path('images/logo_lsp.png')))
                <img src="{{ public_path('images/logo_lsp.png') }}" height="55" alt="">
            @else
                <div style="width:50px; height:50px; border:1px solid #ccc; line-height:50px; text-align:center; font-size:8px;">LSP</div>
            @endif
        </td>
        <td class="lsp-info">
            <div class="lsp-name">LSP MITRA KARYA CEMERLANG</div>
            <div class="lsp-sub">Lembaga Sertifikasi Profesi Pihak Kedua</div>
            <div class="lsp-alamat">
                Ruko Grand Galaxy City, Bekasi, Jawa Barat &nbsp;|&nbsp; Telp: (021) 8243-XXXX &nbsp;|&nbsp; info@lspmkc.or.id
            </div>
        </td>
        <td style="width:12%; text-align:right;">
            @if(file_exists(public_path('images/logo_bnsp.png')))
                <img src="{{ public_path('images/logo_bnsp.png') }}" height="45" alt="">
            @else
                <div style="width:50px; height:50px; border:1px solid #ccc; line-height:50px; text-align:center; font-size:8px;">BNSP</div>
            @endif
        </td>
    </tr>
</table>
<hr class="divider">
<hr class="divider-thin">

{{-- REPORT TITLE --}}
<div class="report-title">Laporan Rincian Permohonan Sertifikasi (APL.01)</div>
<div class="report-meta">Dicetak oleh: <strong>{{ $adminName }}</strong> &nbsp;|&nbsp; Tanggal Cetak: {{ $generatedAt }}</div>

{{-- FILTER SUMMARY TABLE --}}
<div class="summary-container">
    <table class="summary-table">
        <tr>
            <td colspan="2" class="summary-header">Filter Pencarian & Parameter Laporan</td>
            <td colspan="2" class="summary-header">Ringkasan Statistik Rekapitulasi</td>
        </tr>
        <tr>
            <td class="summary-label">Tipe Asesi</td>
            <td class="summary-value">
                @if($filterAsesiType === 'pribadi') Pribadi (Mandiri)
                @elseif($filterAsesiType === 'institusi') Instansi / Lembaga
                @else Semua Tipe Asesi
                @endif
            </td>
            <td class="summary-label">Total Permohonan</td>
            <td class="summary-value" style="font-weight: bold; font-size: 10.5pt;">{{ $total }}</td>
        </tr>
        <tr>
            <td class="summary-label">Periode Waktu</td>
            <td class="summary-value">{{ $periodLabel }}</td>
            <td class="summary-label">Tipe Asesi Pribadi</td>
            <td class="summary-value">{{ $pribadi }} ({{ $total > 0 ? round(($pribadi / $total) * 100) : 0 }}%)</td>
        </tr>
        <tr>
            <td class="summary-label">Status Filter</td>
            <td class="summary-value">
                @if($filterStatus === 'permohonan') Permohonan (Draft / Terkirim / Bayar)
                @elseif($filterStatus === 'proses') Proses Sertifikasi (Dalam Tinjauan)
                @elseif($filterStatus === 'selesai') Selesai (Kompeten)
                @else Semua Status
                @endif
            </td>
            <td class="summary-label">Tipe Asesi Instansi</td>
            <td class="summary-value">{{ $institusi }} ({{ $total > 0 ? round(($institusi / $total) * 100) : 0 }}%)</td>
        </tr>
        <tr>
            <td class="summary-label">Kondisi Khusus</td>
            <td class="summary-value">Status Aktif & Terverifikasi</td>
            <td class="summary-label">Status Selesai</td>
            <td class="summary-value" style="color: #065f46; font-weight: bold;">{{ $selesai }} ({{ $total > 0 ? round(($selesai / $total) * 100) : 0 }}%)</td>
        </tr>
    </table>
</div>

{{-- DATA TABLE --}}
<table class="tbl">
    <colgroup>
        <col style="width: 5%;">
        <col style="width: 25%;">
        <col style="width: 32%;">
        <col style="width: 18%;">
        <col style="width: 20%;">
    </colgroup>
    <thead>
        <tr>
            <th class="center">No</th>
            <th>Nama Asesi</th>
            <th>Skema Sertifikasi</th>
            <th>Tipe Asesi</th>
            <th>Status Pendaftaran</th>
        </tr>
    </thead>
    <tbody>
        @forelse($registrations as $index => $reg)
            @php
                $statusVal = $reg->status;
                $isSelesai = $statusVal === \App\Enums\AssessmentStatus::APL02_APPROVED;
                $isProses = in_array($statusVal, [
                    \App\Enums\AssessmentStatus::APL01_APPROVED,
                    \App\Enums\AssessmentStatus::APL02_SUBMITTED,
                    \App\Enums\AssessmentStatus::APL02_REJECTED
                ]);
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $reg->candidate->name ?? 'User Tidak Ditemukan' }}</strong>
                    @if($reg->candidate->identity_number)
                        <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">NIK: {{ $reg->candidate->identity_number }}</div>
                    @endif
                </td>
                <td>
                    {{ $reg->scheme->name ?? 'Skema Tidak Dipilih' }}
                    <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">Kode: {{ $reg->scheme->code ?? '-' }}</div>
                </td>
                <td>
                    @if($reg->asesi_type === 'institusi')
                        <span class="badge badge-institusi">Instansi</span>
                        @if($reg->institution_name)
                            <div style="font-size: 7.5pt; color: #047857; margin-top: 2px; font-style: italic;">"{{ $reg->institution_name }}"</div>
                        @endif
                    @else
                        <span class="badge badge-pribadi">Pribadi</span>
                    @endif
                </td>
                <td>
                    @if($isSelesai)
                        <span class="badge badge-selesai">Selesai (Lulus)</span>
                    @elseif($isProses)
                        <span class="badge badge-proses">Proses Sertifikasi</span>
                    @else
                        <span class="badge badge-permohonan">Permohonan</span>
                    @endif
                    <div style="font-size: 7.5pt; color: #555; margin-top: 2px; font-family: monospace;">
                        {{ $reg->created_at ? $reg->created_at->translatedFormat('d-m-Y H:i') : '-' }}
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #666; font-style: italic;">
                    Tidak ada data pendaftaran yang cocok dengan kriteria filter laporan.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- FOOTER / SIGNATURES --}}
<table class="footer-table">
    <tr>
        <td></td>
        <td>
            <div class="signature-box">
                <div>Bekasi, {{ now()->translatedFormat('d F Y') }}</div>
                <div style="font-weight: bold; margin-top: 5px;">Mengetahui/Mengesahkan,</div>
                <div style="font-size: 8pt; color: #555;">Sistem Informasi LSP MKC</div>
                <div class="sig-space"></div>
                <div style="border-bottom: 1.5px solid #000; font-weight: bold; display: inline-block; width: 100%;">
                    {{ $adminName }}
                </div>
                <div style="font-size: 8.5pt; color: #444; margin-top: 2px;">Administrator LSP</div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
