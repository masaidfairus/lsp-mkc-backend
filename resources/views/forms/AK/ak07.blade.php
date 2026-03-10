<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.AK.07</title>
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

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 0;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8.5pt;
            line-height: 1.4;
        }

        .cb {
            display: inline-block;
            width: 9px; height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 3px;
            flex-shrink: 0;
        }

        /* Panduan box */
        .panduan-box {
            border: 1px solid #000;
            padding: 7px 10px;
            margin-bottom: 10px;
        }
        .panduan-title {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 6px;
        }
        .panduan-item {
            margin-bottom: 4px;
            padding-left: 12px;
            text-indent: -12px;
            line-height: 1.4;
            font-size: 8pt;
        }
        .panduan-item::before { content: "• "; }

        /* Potensi asesi box */
        .potensi-tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }
        .potensi-tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 8.5pt;
            line-height: 1.4;
            word-wrap: break-word;
        }

        /* Bagian A tabel */
        .tbl-a {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .tbl-a th {
            border: 1px solid #000;
            padding: 4px 5px;
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
        }
        .tbl-a td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8pt;
            line-height: 1.4;
        }

        /* cb-item: checkbox + teks dalam satu baris */
        .cb-item {
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .cb-item:last-child { margin-bottom: 0; }

        .section-title {
            font-weight: bold;
            font-size: 8.5pt;
            margin: 8px 0 4px 0;
        }

        /* Bagian B tabel */
        .tbl-b {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .tbl-b th {
            border: 1px solid #000;
            padding: 4px 5px;
            background: #e8e8e8;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-b td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8pt;
            line-height: 1.4;
        }

        .note { font-size: 7.5pt; font-style: italic; color: #333; margin-top: 4px; margin-bottom: 8px; }

        /* Footer TTD */
        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
        }
        .tbl-ttd td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        .ttd-space { height: 35px; }
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
    $potensi_asesi = $potensi_asesi ?? ''; // 1-5
@endphp

{{-- JUDUL --}}
<div class="judul">FR.AK.07. CEKLIS PENYESUAIAN YANG WAJAR dan BERALASAN</div>

{{-- INFO --}}
<table class="tbl" style="margin-bottom:1rem;">
    <colgroup>
        <col style="width:28%;">
        <col style="width:14%;">
        <col style="width:3%;">
        <col style="width:55%;">
    </colgroup>
    <tr>
        <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
        <td colspan="1">Judul</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="4">{{ $nama_skema }}</td>
    </tr>
    <tr>
        <td colspan="1">Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="4">{{ $kode_skema }}</td>
    </tr>
    <tr>
        <td colspan="3">TUK</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $tuk }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesor</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $nama_asesor }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesi</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $nama_asesi }}</td>
    </tr>
    <tr>
        <td colspan="3">Tanggal</td>
        <td style="text-align:center;">:</td>
        <td colspan="4">{{ $tanggal }}</td>
    </tr>
</table>

{{-- POTENSI ASESI --}}
<table class="potensi-tbl">
    <colgroup>
        <col style="width:22%;">
        <col style="width:5%;">
        <col style="width:73%;">
    </colgroup>
    <tr>
        <td rowspan="5" style="vertical-align:middle; font-weight:bold;" colspan="2">Potensi Asesi</td>
        <td style="text-align:center; vertical-align:middle;" colspan="1"><span class="cb"></span></td>
        <td colspan="3">Hasil pelatihan dan/atau pendidikan, dimana Kurikulum dan fasilitaspraktek mampu telusur terhadap standar kompetensi</td>
    </tr>
    <tr>
        <td style="text-align:center; vertical-align:middle;"><span class="cb"></span></td>
        <td colspan="3">Hasil pelatihan dan/atau pendidikan, dimana kurikulum belum berbasiskompetensi.</td>
    </tr>
    <tr>
        <td style="text-align:center; vertical-align:middle;"><span class="cb"></span></td>
        <td colspan="3">Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi</td>
    </tr>
    <tr>
        <td style="text-align:center; vertical-align:middle;"><span class="cb"></span></td>
        <td colspan="3">Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.</td>
    </tr>
    <tr>
        <td style="text-align:center; vertical-align:middle;"><span class="cb"></span></td>
        <td colspan="3">Pelatihan / belajar mandiri atau otodidak.</td>
    </tr>
</table>

{{-- BAGIAN A --}}
<div class="section-title">Bagian A. Penyesuaian sesuai karakteristik asesi</div>
<table class="tbl-a">
    <colgroup>
        <col style="width:5%;">
        <col style="width:30%;">
        <col style="width:5%;">
        <col style="width:5%;">
        <col style="width:55%;">
    </colgroup>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2" colspan="2">Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi (karakteristik asesi):</th>
        <th colspan="2">Diperlukan penyesuaian</th>
        <th rowspan="2" colspan="2">Keterangan</th>
    </tr>
    <tr>
        <th>Ya</th>
        <th>Tidak</th>
    </tr>

    {{-- Row 1 --}}
    <tr>
        <td style="text-align:center;">1</td>
        <td colspan="2">Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Memerlukan dukungan pembaca, penerjemah, pelayan, penulis. untuk merekam jawaban asesi.</div>
            <div class="cb-item"><span class="cb"></span>Melakukan asesmen verbal (gunakan pertanyaan lisan/wawancara) dengan dilengkapi gambar diagram dan bentuk visual.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan Hasil produksi</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan Ceklis observasi/demonstrasi.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan daftar instruksi terstruktur.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 2 --}}
    <tr>
        <td style="text-align:center;">2</td>
        <td colspan="2">Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 3 --}}
    <tr>
        <td style="text-align:center;">3</td>
        <td colspan="2">Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif seperti: mengoperasikan komputer dan printer, peralatan digital dsb).</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Ceklis observasi/demonstrasi.</div>
            <div class="cb-item"><span class="cb"></span>Pertanyaan lisan.</div>
            <div class="cb-item"><span class="cb"></span>Pertanyaan tertulis.</div>
            <div class="cb-item"><span class="cb"></span>Pertanyaan wawancara.</div>
            <div class="cb-item"><span class="cb"></span>Daftar instruksi terstruktur.</div>
            <div class="cb-item"><span class="cb"></span>Ceklis verifikasi portofolio.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan dukungan operator komputer.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 4 --}}
    <tr>
        <td style="text-align:center;">4</td>
        <td colspan="2">Pelaksanaan asesmen secara fleksibel karena alasan <span style="text-decoration:underline;">keletihan</span> atau keperluan pengobatan.</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Menggunakan juru tulis.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan <span style="text-decoration:underline;">kamaramen</span> perekam vidio/ataudio.</div>
            <div class="cb-item"><span class="cb"></span>Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekerjaan dalam asesmen.</div>
            <div class="cb-item"><span class="cb"></span>Melakukan tugas pekerjaan dalam asesmen dengan waktu lebih pendek.</div>
        </td>
    </tr>

    {{-- Row 5 --}}
    <tr>
        <td style="text-align:center;">5</td>
        <td colspan="2">Penyediaan peralatan asesmen berupa braille, audio/<em>video-tape</em>.</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan pertanyaan lisan.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan pertanyaan wawancara.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 6 --}}
    <tr>
        <td style="text-align:center;">6</td>
        <td colspan="2">Penyesuaian tempat fisik/lingkungan asesmen</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Pertanyaan lisan.</div>
            <div class="cb-item"><span class="cb"></span>Pertanyaan tulis.</div>
            <div class="cb-item"><span class="cb"></span>Pertanyaan wawancara.</div>
            <div class="cb-item"><span class="cb"></span>Ceklis Verifikasi portofolio.</div>
            <div class="cb-item"><span class="cb"></span>Ceklis reviu produk.</div>
            <div class="cb-item"><span class="cb"></span>Daftar instruksi terstruktur.</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 7 --}}
    <tr>
        <td style="text-align:center;">7</td>
        <td colspan="2">Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Menggunakan studi kasus/daftar instruksi terstruktur.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan asesor dengan jenis kelamin yang sama dengan asesi.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamin (tidak boleh memberi tanda tambahan pada instrumen asesmen yang digunakan dengan tujuan untuk membedakan jenis kelamin).</div>
            <div class="cb-item"><span class="cb"></span>......................................................</div>
        </td>
    </tr>

    {{-- Row 8 --}}
    <tr>
        <td style="text-align:center;">8</td>
        <td colspan="2">Pertimbangan budaya/tradisi/agama.</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td colspan="2">
            <div class="cb-item"><span class="cb"></span>Menggunakan studi kasus <em>daftar instruksi terstruktur</em>.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan asesor tanpa pertimbangan budaya/tradisi/agama.</div>
            <div class="cb-item"><span class="cb"></span>Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama).</div>
        </td>
    </tr>
</table>

{{-- BAGIAN B --}}
<div class="page-break"></div>
<div class="section-title">Bagian B. Penyesuaian rencana asesmen sesuai acuan pembanding, potensi asesi dan konteks asesi.</div>

<table class="tbl-b">
    <colgroup>
        <col style="width:40%;">
        <col style="width:7%;">
        <col style="width:7%;">
        <col style="width:46%;">
    </colgroup>
    <tr>
        <th colspan="2">Rekaman Rencana Asesmen</th>
        <th>Ya</th>
        <th>Tidak</th>
        <th>Keputusan Penyesuaian</th>
    </tr>

    {{-- B1 --}}
    <tr>
        <td colspan="2">1. Apakah rekaman rencana asesmen tervalidasi dibuat menggunakan acuan pembanding, minimal standar kompetensi kerja?</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td>
            Standar Industri:<br><br>
            SOP (jika ada):<br><br>
            Regulasi Teknik:
        </td>
    </tr>

    {{-- B2 --}}
    <tr>
        <td colspan="2">2. Apakah rekaman rencana asesmen tervalidasi sudah sesuai dengan potensi asesi yang akan diujikan?</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td>
            Metode asesmen:<br><br><br>
            Instrumen Asesmen:<br>
            FR.IA..........
        </td>
    </tr>

    {{-- B3 --}}
    <tr>
        <td colspan="2">3. Apakah rekaman rencana asesmen tervalidasi sudah sesuai dengan konteks asesi (berdasarkan rekaman APL.01 tervalidasi LSP dan rekaman APL.02 tervalidasi asesi)?</td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td style="text-align:center;"><span class="cb"></span></td>
        <td>
            Metode asesmen:<br><br><br>
            Instrumen Asesmen:<br>
            FR.IA..........
        </td>
    </tr>
</table>

{{-- Ringkasan hasil --}}
<table class="tbl" style="margin-bottom:10px;">
    <tr>
        <td style="line-height:1.7; font-size:8pt;">
            &bull; &nbsp; Hasil penyesuaian sesuai karakteristik asesi<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Acuan Pembanding Asesmen: ( tuliskan nama acuan pembanding)<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Metode Asesmen: ( tuliskan nama metode asesmen)<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Instrumen Asesmen: ( tuliskan nama formulir instrumen asesmen )<br><br>
            &bull; &nbsp; Hasil penyesuaian rencana asesmen sesuai acuan pembanding, potensi asesi dan konteks asesi.<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Acuan Pembanding Asesmen: ( tuliskan nama acuan pembanding)<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Metode Asesmen: ( tuliskan nama metode asesmen)<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&bull; &nbsp; Instrumen Asesmen: ( tuliskan nama formulir instrumen asesmen )
        </td>
    </tr>
</table>

{{-- TTD --}}
<table class="tbl-ttd">
    <colgroup>
        <col style="width:30%;">
        <col style="width:20%;">
        <col style="width:30%;">
        <col style="width:20%;">
    </colgroup>
    <tr>
        <td>Nama Asesor :</td>
        <td class="ttd-space">Tanggal dan Tanda Tangan Asesor:</td>
    </tr>
    <tr>
        <td>Nama Asesi:</td>
        <td class="ttd-space">Tanggal dan Tanda Tangan Asesi:</td>
    </tr>
</table>

</body>
</html>