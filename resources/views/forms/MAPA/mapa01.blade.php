<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.MAPA.01</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
        }
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
            size: A4 portrait;
        }

        .page-break {
            page-break-before: always;
        }

        /* ─────────────────────────────────────
           SHARED
        ───────────────────────────────────── */
        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 4px;
            position: relative;
        }

        .cb-checked::after {
            display: inline-block;
            content: '';
            position: absolute;
            left: 3px;
            top: 0px;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .cb-row {
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .cb-row:last-child {
            margin-bottom: 0;
        }

        /* ─────────────────────────────────────
           BAGIAN 1 — 3 kolom: no | label | konten
        ───────────────────────────────────── */
        .tbl-skema {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .tbl-skema td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
        }
        .tbl-b1 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            table-layout: fixed;
        }

        .tbl-b1 td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .tbl-b1 .no-cell {
            width: 6%;
            text-align: center;
            vertical-align: top;
            font-weight: bold;
            background: #f5f5f5;
        }

        .tbl-b1 .label-cell {
            width: 20%;
            font-weight: bold;
            vertical-align: top;
            background: #fafafa;
        }

        .tbl-b1 .content-cell {
            width: 74%;
            vertical-align: top;
        }

        .tbl-b1 .section-header {
            background: #e8e8e8;
            font-weight: bold;
            font-size: 9.5pt;
            padding: 6px 8px;
        }

        .inner-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .inner-tbl td {
            border: 1px solid #bbb;
            padding: 4px 6px;
            vertical-align: top;
        }

        .inner-tbl td:first-child {
            width: 28%;
            font-weight: bold;
            background: #f9f9f9;
        }

        /* ─────────────────────────────────────
           BAGIAN 2 — compact portrait
        ───────────────────────────────────── */
        .b2-header {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #000;
        }

        .tbl-unit-list {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
            font-size: 8.5pt;
        }

        .tbl-unit-list th {
            border: 1px solid #000;
            padding: 5px 6px;
            background: #ddd;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tbl-unit-list td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .kp-label {
            background: #f0f0f0;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #000;
        }
        .tbl-unit-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .tbl-unit-list th { border: 1px solid #000; padding: 5px; background: #ddd; font-weight: bold; }
        .tbl-unit-list td { border: 1px solid #000; padding: 4px 6px; }

        .tbl-metode {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        .tbl-metode th,
        .tbl-metode td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 8pt;
            text-align: center;
            vertical-align: middle;
        }

        .tbl-metode th {
            background: #ececec;
            font-weight: bold;
        }

        .tbl-metode td.left {
            text-align: left;
        }

        /* ─────────────────────────────────────
           BAGIAN 3 — card style
        ───────────────────────────────────── */
        .b3-title {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #000;
        }

        .tbl-b3 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
            font-size: 8.5pt;
        }

        .tbl-b3 td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .tbl-b3 .item-no {
            width: 6%;
            font-weight: bold;
            text-align: center;
            background: #f0f0f0;
            vertical-align: top;
        }

        .tbl-b3 .item-label {
            width: 38%;
            font-weight: bold;
            background: #fafafa;
        }

        .tbl-b3 .item-val {
            width: 56%;
        }

        .tbl-konfirmasi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }

        .tbl-konfirmasi th {
            border: 1px solid #000;
            padding: 5px 7px;
            background: #ddd;
            font-weight: bold;
            text-align: center;
        }

        .tbl-konfirmasi td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
        }
        .tbl-konfirmasi th { border: 1px solid #000; padding: 6px; background: #ddd; font-weight: bold; text-align: center; }
        .tbl-konfirmasi td { border: 1px solid #000; padding: 6px; vertical-align: middle; }

        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .tbl-ttd th {
            border: 1px solid #000;
            padding: 5px 7px;
            background: #ddd;
            font-weight: bold;
            text-align: center;
        }

        .tbl-ttd td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            text-align: center;
        }

        .ttd-space {
            height: 45px;
        }

        .italic-note {
            font-size: 7.5pt;
            font-style: italic;
            margin-bottom: 8px;
            color: #333;
        }

        /* tbl — dipakai bagian 3 */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
        }

        .tbl td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .tbl-ttd th { border: 1px solid #000; padding: 6px; background: #ddd; font-weight: bold; text-align: center; }
        .tbl-ttd td { border: 1px solid #000; padding: 6px; vertical-align: top; text-align: center; }
        .sig-image { max-height: 45px; display: block; margin: 0 auto; }
    </style>
</head>

<body>

    @php
$nama_skema = $nama_skema ?? '';
$kode_skema = $kode_skema ?? '';
$tujuan_asesmen = $tujuan_asesmen ?? '';
$kelompok_pekerjaan = $kelompok_pekerjaan ?? [];

$karakteristik_khusus = $karakteristik_khusus ?? 'Ada / Tidak Ada*';
$kebutuhan_kontekstualisasi = $kebutuhan_kontekstualisasi ?? 'Ada / Tidak Ada*';
$saran_pelatihan = $saran_pelatihan ?? 'Ada / Tidak Ada*';
$penyesuaian_perangkat = $penyesuaian_perangkat ?? 'Ada / Tidak Ada*';
$peluang_terintegrasi = $peluang_terintegrasi ?? 'Ada / Tidak Ada*';

$penyusun = $penyusun ?? [['nama' => '', 'met_number' => ''], ['nama' => '', 'met_number' => '']];
$validator = $validator ?? [['nama' => '', 'met_number' => ''], ['nama' => '', 'met_number' => '']];
    @endphp

    {{-- ══════════════════════════════
    BAGIAN 1: PENDEKATAN ASESMEN
    Layout: 3 kolom (no | label | konten)
    ══════════════════════════════ --}}

    <div class="judul-utama">FR.MAPA.01 – MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</div>

    <table class="tbl-skema">
        <colgroup>
            <col style="width:30%;">
            <col style="width:12%;">
            <col style="width:3%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td rowspan="2" colspan="2" style="font-weight:bold; vertical-align:middle;">Skema Sertifikasi<br><span
                    style="font-weight:normal; font-size:8.5pt;">(KKNI/Okupasi/Klaster)</span></td>
            <td>Judul</td>
            <td style="text-align:center;" colspan="1">:</td>
            <td style="font-weight:bold;" colspan="4">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;" colspan="4">{{ $kode_skema }}</td>
        </tr>
    </table>

    <table class="tbl-b1">
        <colgroup>
            <col style="width:6%;">
            <col style="width:20%;">
            <col style="width:74%;">
        </colgroup>
        <tr>
            <td colspan="7" class="section-header">1. &nbsp;&nbsp; Menentukan Pendekatan Asesmen</td>
        </tr>
        <tr>
            <td class="no-cell" colspan="1">1.1</td>
            <td class="label-cell" colspan="1">Asesi</td>
            <td class="content-cell" colspan="5">
                <div class="cb-row"><span
                        class="{{ strpos($asesi_option, 'Hasil pelatihan') !== false && strpos($asesi_option, 'mampu telusur') !== false ? 'cb-checked' : 'cb' }}"></span>Hasil
                    pelatihan dan/atau pendidikan, dimana Kurikulum dan
                    fasilitas praktek mampu telusur terhadap standar kompetensi</div>
                <div class="cb-row"><span
                        class="{{ strpos($asesi_option, 'Hasil pelatihan') !== false && strpos($asesi_option, 'belum berbasis') !== false ? 'cb-checked' : 'cb' }}"></span>Hasil
                    pelatihan dan/atau pendidikan, dimana kurikulum belum
                    berbasis kompetensi</div>
                <div class="cb-row"><span
                        class="{{ strpos($asesi_option, 'Pekerja berpengalaman') !== false && strpos($asesi_option, 'mampu telusur') !== false ? 'cb-checked' : 'cb' }}"></span>Pekerja
                    berpengalaman, dimana berasal dari industri/tempat
                    kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi</div>
                <div class="cb-row"><span
                        class="{{ strpos($asesi_option, 'Pekerja berpengalaman') !== false && strpos($asesi_option, 'belum berbasis') !== false ? 'cb-checked' : 'cb' }}"></span>Pekerja
                    berpengalaman, dimana berasal dari industri/tempat
                    kerja yang dalam operasionalnya belum berbasis kompetensi</div>
                <div class="cb-row"><span
                        class="{{ strpos($asesi_option, 'otodidak') !== false ? 'cb-checked' : 'cb' }}"></span>Pelatihan
                    / belajar mandiri atau otodidak</div>
            </td>
        </tr>
        <tr>
            <td class="no-cell"></td>
            <td class="label-cell">Tujuan Asesmen</td>
            <td class="content-cell" colspan="5">
                <div class="cb-row"><span
                        class="{{ $tujuan_asesmen === 'Sertifikasi' ? 'cb-checked' : 'cb' }}"></span>Sertifikasi</div>
                <div class="cb-row"><span class="{{ $tujuan_asesmen === 'PKT' ? 'cb-checked' : 'cb' }}"></span>Pengakuan
                    Kompetensi Terkini (PKT)</div>
                <div class="cb-row"><span class="{{ $tujuan_asesmen === 'RPL' ? 'cb-checked' : 'cb' }}"></span>Rekognisi
                    Pembelajaran Lampau (RPL)</div>
                <div class="cb-row"><span
                        class="{{ $tujuan_asesmen === 'Lainnya' ? 'cb-checked' : 'cb' }}"></span>Lainnya</div>
            </td>
        </tr>
        <tr>
            <td class="no-cell"></td>
            <td class="label-cell">Konteks Asesmen</td>
            <td class="content-cell" style="padding:0;" colspan="5">
                <table class="inner-tbl">
                    <tr>
                        <td style="text-align: center;" colspan="1">Lingkungan</td>
                        <td
                            style="display: flex; justify-content: center; align-items: center; align-content: center; padding: 1rem;">
                            <span style="display: inline-block;">
                                <span class="{{ $lingkungan === 'Tempat kerja nyata' ? 'cb-checked' : 'cb' }}"></span>
                                <span>Tempat kerja nyata </span>
                            </span>
                            <span style="display: inline-block;">
                                <span class="{{ $lingkungan === 'Tempat kerja' ? 'cb-checked' : 'cb' }}"></span>
                                <span>Tempat kerja </span>
                            </span>
                            <span
                                style="display: inline-block; margin-left: 1rem; justify-content: center; align-items: center; align-content: center;">
                                <span class="{{ $lingkungan === 'Simulasi' ? 'cb-checked' : 'cb' }}"></span>
                                <span>Simulasi</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</td>
                        <td
                            style="display: flex; justify-content: center; align-items: center; align-content: center; padding: 2rem 1rem;">
                            <span style="display: inline-block;">
                                <span class="{{ $peluang === 'Tersedia' ? 'cb-checked' : 'cb' }}"></span>
                                Tersedia
                            </span>
                            <span style="display: inline-block; padding-left: 1rem; ">
                                <span class="{{ $peluang === 'Terbatas' ? 'cb-checked' : 'cb' }}"></span>
                                Terbatas
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Hubungan antara standar kompetensi dan:</td>
                        <td>
                            <div class="cb-row"><span
                                    class="{{ $hubungan === 'Bukti untuk mendukung asesmen' ? 'cb-checked' : 'cb' }}"></span>Bukti
                                untuk mendukung asesmen</div>
                            <div class="cb-row"><span
                                    class="{{ $hubungan === 'Aktivitas kerja di tempat kerja Asesi' ? 'cb-checked' : 'cb' }}"></span>Aktivitas
                                kerja di tempat kerja Asesi</div>
                            <div class="cb-row"><span
                                    class="{{ $hubungan === 'Kegiatan Pembelajaran' ? 'cb-checked' : 'cb' }}"></span>Kegiatan
                                Pembelajaran</div>
                        </td>
                    </tr>
                    <tr>
                        <td>Siapa yang melakukan asesmen</td>
                        <td>
                            <div class="cb-row"><span
                                    class="{{ $pelaku_asesmen === 'Lembaga Sertifikasi' ? 'cb-checked' : 'cb' }}"></span>Lembaga
                                Sertifikasi</div>
                            <div class="cb-row"><span
                                    class="{{ $pelaku_asesmen === 'Organisasi Pelatihan' ? 'cb-checked' : 'cb' }}"></span>Organisasi
                                Pelatihan</div>
                            <div class="cb-row"><span
                                    class="{{ $pelaku_asesmen === 'Asesor Perusahaan' ? 'cb-checked' : 'cb' }}"></span>Asesor
                                Perusahaan</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="no-cell"></td>
            <td class="label-cell">Konfirmasi dengan orang yang relevan</td>
            <td class="content-cell" colspan="5">
                {{-- Manajer Sertifikasi LSP --}}
                <div class="cb-row">
                    <span class="{{ !is_null($lsp_manager['user_id']) ? 'cb-checked' : 'cb' }}"></span>
                    Manajer sertifikasi LSP
                </div>

                {{-- Master Asesor --}}
                <div class="cb-row">
                    <span class="{{ !is_null($master_assessor['user_id']) ? 'cb-checked' : 'cb' }}"></span>
                    Master Asesor / Master Trainer / Lead Asesor Kompetensi
                </div>

                {{-- Manajer Pelatihan --}}
                <div class="cb-row">
                    <span class="{{ !is_null($training_manager['user_id']) ? 'cb-checked' : 'cb' }}"></span>
                    Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar
                </div>

                {{-- Workplace Supervisor --}}
                <div class="cb-row">
                    <span class="{{ !is_null($workplace_supervisor['user_id']) ? 'cb-checked' : 'cb' }}"></span>
                    Manajer atau supervisor ditempat kerja
                </div>
            </td>
        </tr>
        <tr>
            <td class="no-cell">1.2</td>
            <td class="label-cell">Standar Industri atau Tempat Kerja</td>
            <td class="content-cell" colspan="5">
                <div class="cb-row"><span
                        class="{{ strpos($benchmark_standards, 'Standar Kompetensi') !== false ? 'cb-checked' : 'cb' }}"></span>Standar
                    Kompetensi</div>
                <div class="cb-row"><span
                        class="{{ strpos($benchmark_standards, 'Kriteria asesmen') !== false ? 'cb-checked' : 'cb' }}"></span>Kriteria
                    asesmen dari kurikulum pelatihan</div>
                <div class="cb-row"><span
                        class="{{ strpos($benchmark_standards, 'Spesifikasi kinerja') !== false ? 'cb-checked' : 'cb' }}"></span>Spesifikasi
                    kinerja suatu perusahaan atau industri</div>
                <div class="cb-row"><span
                        class="{{ strpos($benchmark_standards, 'Spesifikasi Produk') !== false ? 'cb-checked' : 'cb' }}"></span>Spesifikasi
                    Produk</div>
                <div class="cb-row"><span
                        class="{{ strpos($benchmark_standards, 'Pedoman khusus') !== false ? 'cb-checked' : 'cb' }}"></span>Pedoman
                    khusus</div>
            </td>
        </tr>
    </table>


    {{-- ══════════════════════════════
    BAGIAN 2: RENCANA ASESMEN
    Layout: unit list + tabel metode 10 kolom compact
    Tiap kelompok = halaman baru
    ══════════════════════════════ --}}

    <div class="page-break"></div>
    <div class="b2-header">2. &nbsp;&nbsp; Mempersiapkan Rencana Asesmen</div>

    @foreach($kelompok_pekerjaan as $kp)

        @if(!$loop->first)
            <div class="page-break"></div>
            <div class="b2-header">2. &nbsp;&nbsp; Mempersiapkan Rencana Asesmen</div>
        @endif

        <div class="kp-label">Kelompok Pekerjaan {{ $kp['no'] }} &nbsp;—&nbsp; {{ $kp['nama'] }}</div>

        {{-- Daftar unit --}}
        <table class="tbl-unit-list" style="border-bottom:none;">
            <colgroup>
                <col style="width:6%;">
                <col style="width:25%;">
                <col style="width:69%;">
            </colgroup>
            <tr>
                <th colspan="1">No.</th>
                <th colspan="2">Kode Unit</th>
                <th style="text-align:left; padding-left:6px;" colspan="2">Judul Unit</th>
            </tr>
            @foreach($kp['unit'] as $i => $unit)
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}.</td>
                    <td colspan="2">{{ $unit['kode'] }}</td>
                    <td colspan="2">{{ $unit['judul'] }}</td>
                </tr>
            @endforeach
        </table>

        {{-- Tabel Metode Asesmen — 11 kolom (Updated based on SS) --}}
        <table class="tbl-metode">
            <colgroup>
                <col style="width:20%;"> {{-- Unit Kompetensi --}}
                <col style="width:15%;"> {{-- Bukti-Bukti --}}
                <col style="width:3%;"> {{-- L --}}
                <col style="width:3%;"> {{-- TL --}}
                <col style="width:3%;"> {{-- T --}}
                <col style="width:11%;"> {{-- CL --}}
                <col style="width:11%;"> {{-- DIT --}}
                <col style="width:11%;"> {{-- Tanya Jawab --}}
                <col style="width:11%;"> {{-- CVP --}}
                <col style="width:11%;"> {{-- CRP --}}
                <col style="width:11%;"> {{-- VPK --}}
            </colgroup>
            <tr>
                <th colspan="2" rowspan="3" style="text-align:center; padding:5px 7px; font-size: .6rem;">Unit Kompetensi</th>
                <th colspan="2" rowspan="3" style="font-size: .6rem">Bukti-Bukti diidentifikasi berdasarkan Kriteria Unjuk Kerja dan Pendekatan Asesmen.</th>
                <th colspan="3">Jenis Bukti</th>
                {{-- Colspan berubah menjadi 6 karena sekarang ada 6 kolom metode --}}
                <th colspan="12" style="background:#d4d4d4;">
                    Metode dan Perangkat Asesmen
                </th>
            </tr>
            <tr>
                <th rowspan="2">L</th>
                <th rowspan="2">TL</th>
                <th rowspan="2">T</th>  
                <th colspan="2" >Observasi langsung<br>(CL)</th>
                <th colspan="2" >Kegiatan Terstruktur<br>(DIT)</th>
                <th colspan="2" >Tanya Jawab<br>(DPL/DPT/PW)</th>
                <th colspan="2" >Verifikasi Portfolio<br>(CVP)</th>
                <th colspan="2" >Reviu Produk<br>(CRP)</th>
                <th colspan="2" >Verifikasi Pihak Ketiga<br>(VPK)</th>
            </tr>
            <tr>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Kerja nyata/aktivitas waktu nyata</th>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Simulasi, proyek, presentasi</th>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Tertulis, lisan, wawancara</th>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Sampel kerja, produk, jurnal</th>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Produk hasil proyek/kerja</th>
                <th colspan="2" style="font-weight:normal; background:#f5f5f5; font-size:7pt;">Testimoni, laporan, otentikasi</th>
            </tr>

            @foreach($kp['unit'] as $i => $unit)
                @php
                    $bukti = $unit['bukti'] ?? ($unit['evidence'] ?? '');
                    $jbRaw = $unit['jenis_bukti_select'] ?? ($unit['evidence_type'] ?? '');
                    $jb = is_array($jbRaw) ? implode('/', $jbRaw) : (string)$jbRaw;
                    $metode = $unit['metode'] ?? ($unit['assessment_methods'] ?? []);
                    if (!is_array($metode)) $metode = [];
                @endphp
                <tr>
                    <td colspan="2" class="left" style="font-size: 6pt; padding: 3px; line-height:1.2;">{{ $unit['judul'] ?? '' }}</td>
                    <td colspan="2" style="text-align: left; font-size: 6pt; padding: 3px; line-height:1.2;">{{ $bukti }}</td>
                    <td style="text-align: center;">{{ $jb === 'L' ? 'L' : '' }}</td>
                    <td style="text-align: center;">{{ $jb === 'TL' ? 'TL' : '' }}</td>
                    <td style="text-align: center;">{{ $jb === 'T' ? 'T' : '' }}</td>

                    {{-- 1. Observasi Langsung --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Observasi langsung' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>

                    {{-- 2. Kegiatan Terstruktur --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Kegiatan Terstruktur' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>

                    {{-- 3. Tanya Jawab --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Tanya Jawab' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>

                    {{-- 4. Verifikasi Portfolio (CVP) --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Verifikasi Portfolio' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>

                    {{-- 5. Reviu Produk (CRP) --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Reviu Produk' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>

                    {{-- 6. Verifikasi Pihak Ketiga (VPK) --}}
                    <td colspan="2" style="font-size: 7pt; padding: 2px; text-align:center;">
                        @foreach($metode as $m)
                            @if(($m['method'] ?? '') === 'Verifikasi Pihak Ketiga' && ($m['answer'] ?? '')) {{ $m['answer'] }} @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>

    @endforeach


    {{-- ══════════════════════════════
    BAGIAN 3: MODIFIKASI & FOOTER
    Layout: 3-kolom card + konfirmasi + TTD
    ══════════════════════════════ --}}

    <div class="page-break"></div>
    <div class="b3-title">3. &nbsp;&nbsp; Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi</div>

    <table class="tbl" style="margin-bottom:8px;">
        <colgroup>
            <col style="width:45%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td>3.1. a. Karakteristik Kandidat:</td>
            <td style="min-height:30px;">{{ $karakteristik_khusus ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding-left:20px;">b. Kebutuhan kontekstualisasi terkait tempat kerja:</td>
            <td>{{ $kebutuhan_kontekstualisasi ?? '' }}</td>
        </tr>
        <tr>
            <td>3.2. Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan</td>
            <td>{{ $saran_pelatihan ?? '' }}</td>
        </tr>
        <tr>
            <td>3.3. Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi</td>
            <td>{{ $penyesuaian_perangkat ?? '' }}</td>
        </tr>
        <tr>
            <td>3.4. Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk
                alat asesmen</td>
            <td>{{ $peluang_terintegrasi ?? '' }}</td>
        </tr>
    </table>

    <div style="font-weight:bold; font-size:9pt; margin-bottom:5px;">Konfirmasi dengan Orang yang Relevan</div>
    <table class="tbl-konfirmasi" style="margin-bottom:14px;">
        <colgroup>
            <col style="width:55%;">
            <col style="width:20%;">
            <col style="width:25%;">
        </colgroup>
        <tr>
            <th style="text-align:left; padding-left:8px;">Orang yang Relevan</th>
            <th>Nama</th>
            <th>Tandatangan &amp; Tanggal</th>
        </tr>
        <tr>
            <td>Manajer sertifikasi LSP</td>
            <td>{{ $lsp_manager['name'] ?? '' }}</td>
            <td>{{ $lsp_manager['date'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Master Asesor / Master Trainer / Lead Asesor Kompetensi</td>
            <td>{{ $master_assessor['name'] ?? '' }}</td>
            <td>{{ $master_assessor['date'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</td>
            <td>{{ $training_manager['name'] ?? '' }}</td>
            <td>{{ $training_manager['date'] ?? '' }}</td>
        </tr>
        <tr>
            <td>Manajer atau supervisor ditempat kerja</td>
            <td>{{ $workplace_supervisor['name'] ?? '' }}</td>
            <td>{{ $workplace_supervisor['date'] ?? '' }}</td>
        </tr>
    </table>

    <div style="font-weight:bold; font-size:9pt; margin-bottom:5px;">Penyusun dan Validator</div>
    <table class="tbl-ttd">
        <colgroup>
            <col style="width:14%;">
            <col style="width:6%;">
            <col style="width:28%;">
            <col style="width:24%;">
            <col style="width:28%;">
        </colgroup>
        <tr>
            <th>Status</th>
            <th>No.</th>
            <th>Nama</th>
            <th>Nomor MET</th>
            <th>Tanda Tangan &amp; Tanggal</th>
        </tr>
        <tr>
            <td rowspan="2" style="font-weight:bold; vertical-align:middle; text-align:center;">PENYUSUN</td>
            <td style="text-align:center;">1</td>
            <td>{{ $penyusun[0]['name'] ?? '' }}</td>
            <td>{{ $penyusun[0]['met_number'] ?? '' }}</td>
            <td style="text-align:center; vertical-align:middle; padding: 5px;">
                @if(isset($penyusun[0]['qr_code']) && $penyusun[0]['qr_code'])
                    <img src="{{ $penyusun[0]['qr_code'] }}" style="width:40px; height:40px;"><br>
                    <span style="font-size:7pt;">{{ $penyusun[0]['date'] ?? '' }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">2</td>
            <td>{{ $penyusun[1]['name'] ?? '' }}</td>
            <td>{{ $penyusun[1]['met_number'] ?? '' }}</td>
            <td style="text-align:center; vertical-align:middle; padding: 5px;">
                @if(isset($penyusun[1]['qr_code']) && $penyusun[1]['qr_code'])
                    <img src="{{ $penyusun[1]['qr_code'] }}" style="width:40px; height:40px;"><br>
                    <span style="font-size:7pt;">{{ $penyusun[1]['date'] ?? '' }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="font-weight:bold; vertical-align:middle; text-align:center;">VALIDATOR</td>
            <td style="text-align:center;">1</td>
            <td>{{ $validator[0]['name'] ?? '' }}</td>
            <td>{{ $validator[0]['met_number'] ?? '' }}</td>
            <td style="text-align:center; vertical-align:middle; padding: 5px;">
                @if(isset($validator[0]['qr_code']) && $validator[0]['qr_code'])
                    <img src="{{ $validator[0]['qr_code'] }}" style="width:40px; height:40px;"><br>
                    <span style="font-size:7pt;">{{ $validator[0]['date'] ?? '' }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">2</td>
            <td>{{ $validator[1]['name'] ?? '' }}</td>
            <td>{{ $validator[1]['met_number'] ?? '' }}</td>
            <td style="text-align:center; vertical-align:middle; padding: 5px;">
                @if(isset($validator[1]['qr_code']) && $validator[1]['qr_code'])
                    <img src="{{ $validator[1]['qr_code'] }}" style="width:40px; height:40px;"><br>
                    <span style="font-size:7pt;">{{ $validator[1]['date'] ?? '' }}</span>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>
