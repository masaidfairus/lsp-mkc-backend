<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.APL.01 - Permohonan Sertifikasi Kompetensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            background: #fff;
            padding: 6rem;
        }

        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }

        /* ===== JUDUL UTAMA ===== */
        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 14px;
        }

        /* ===== BAGIAN HEADING ===== */
        .bagian-heading {
            font-size: 10pt;
            font-weight: bold;
            margin: 14px 0 4px 0;
        }

        .bagian-desc {
            font-size: 9pt;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        /* ===== SECTION TITLE (a. b. c.) ===== */
        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            margin: 10px 0 6px 0;
        }

        /* ===== FORM GARIS (Bagian 1) ===== */
        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table tr td {
            padding: 4px 4px 2px 4px;
            vertical-align: bottom;
            font-size: 9.5pt;
        }

        .form-table td.lbl {
            width: 36%;
            vertical-align: bottom;
            padding-bottom: 3px;
            padding-left: 16px;
        }

        .form-table td.colon {
            width: 4%;
            text-align: center;
            vertical-align: bottom;
            padding-bottom: 3px;
        }

        .form-table td.val {
            width: 60%;
            border-bottom: 1px solid #000;
            vertical-align: bottom;
            padding-bottom: 2px;
            min-height: 16px;
        }

        .form-table tr.spacer td {
            height: 5px;
            border: none;
        }

        /* ===== CHECKBOX ===== */
        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
        }
        
        .checkbox-checked {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
            position: relative;
        }

        .checkbox-checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 0px;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* ===== TABEL BORDER (Bagian 2 & 3) ===== */
        .tbl-border {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
        }

        .tbl-border td,
        .tbl-border th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .tbl-border th {
            font-weight: bold;
            text-align: center;
        }

        .tbl-border td.center {
            text-align: center;
        }

        /* ===== PAGE BREAK ===== */
        .page-break {
            page-break-before: always;
        }

        /* ===== REKOMENDASI TABLE ===== */
        .tbl-rekom {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 14px;
        }

        .tbl-rekom td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
        }

        /* ===== TTD ===== */
        .ttd-line {
            border-bottom: 1px solid #000;
            margin: 40px 8px 4px 8px;
        }
    </style>
</head>

<body>

    @php
        $unit_kompetensi = $unit_kompetensi ?? [];
        $persyaratan_dasar = $persyaratan_dasar ?? [];
        $bukti_administratif = $bukti_administratif ?? [];
    @endphp
    {{-- ============================================================ --}}
    {{-- JUDUL UTAMA --}}
    {{-- ============================================================ --}}
    <div class="judul-utama">FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI</div>

    {{-- ============================================================ --}}
    {{-- BAGIAN 1: DATA PEMOHON --}}
    {{-- ============================================================ --}}
    <div class="bagian-heading">Bagian 1 : Rincian Data Pemohon Sertifikasi</div>
    <div class="bagian-desc">
        Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.
    </div>

    {{-- A. DATA PRIBADI --}}
    <div class="section-title">a. &nbsp; Data Pribadi</div>

    <table class="form-table">
        <tr>
            <td class="lbl">Nama lengkap</td>
            <td class="colon">:</td>
            <td class="val">{{ $nama }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">No. KTP/NIK/Paspor</td>
            <td class="colon">:</td>
            <td class="val">{{ $nik }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Tempat / tgl. Lahir</td>
            <td class="colon">:</td>
            <td class="val">{{ $tempat_lahir }},&nbsp; {{ $tanggal_lahir }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Jenis kelamin</td>
            <td class="colon">:</td>
            <td class="val">{{ $jenis_kelamin }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Kebangsaan</td>
            <td class="colon">:</td>
            <td class="val">{{ $kebangsaan ?? 'Indonesia' }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        {{-- Alamat baris 1 - FIX: vertical-align top supaya teks wrap ke bawah, bukan ngangkat --}}
        <tr>
            <td class="lbl" style="vertical-align:top; padding-top:3px;">Alamat rumah</td>
            <td class="colon" style="vertical-align:top; padding-top:3px;">:</td>
            <td class="val" style="vertical-align:top; padding-top:3px;">{{ $alamat }}</td>
        </tr>
        {{-- Alamat baris 2 --}}
        <tr>
            <td class="lbl" style="vertical-align:top; padding-top:3px;"></td>
            <td class="colon" style="vertical-align:top; padding-top:3px;"></td>
            <td class="val" style="vertical-align:top; padding-top:3px;">
                {{ $rt_rw ? 'RT/RW ' . $rt_rw . ', ' : '' }}{{ $kelurahan }}, {{ $kecamatan }}</td>
        </tr>
        {{-- Alamat baris 3 + kode pos --}}
        <tr>
            <td class="lbl" style="vertical-align:top; padding-top:3px;"></td>
            <td class="colon" style="vertical-align:top; padding-top:3px;">:</td>
            <td style="vertical-align:top; padding-top:3px; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td
                            style="width:52%; border-bottom:2px solid #000; padding-bottom:2px; font-size:9.5pt; vertical-align:top; word-wrap:break-word;">
                            {{ $kabupaten }}, {{ $provinsi }}
                        </td>
                        <td style="width:2%;"></td>
                        <td
                            style="width:18%; font-size:9pt; vertical-align:bottom; padding-bottom:3px; white-space:nowrap;">
                            Kode pos :</td>
                        <td
                            style="width:28%; border-bottom:2px solid #000; padding-bottom:2px; text-align:center; font-size:9.5pt;">
                            {{ $kode_pos }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        {{-- Telepon --}}
        <tr>
            <td class="lbl">No. Telepon/E-mail</td>
            <td class="colon">:</td>
            <td style="vertical-align:bottom; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">Rumah
                            :</td>
                        <td style="width:32%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                        <td
                            style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">
                            Kantor :</td>
                        <td style="width:32%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                    </tr>
                    <tr style="height:5px;"></tr>
                    <tr>
                        <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">HP :
                        </td>
                        <td style="border-bottom:1px solid #000; padding-bottom:2px; font-size:9.5pt;">{{ $no_telp }}
                        </td>
                        <td
                            style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">
                            E-mail :</td>
                        <td style="border-bottom:1px solid #000; padding-bottom:2px; font-size:9.5pt;">{{ $email }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Kualifikasi Pendidikan</td>
            <td class="colon">:</td>
            <td class="val">{{ $pendidikan }}</td>
        </tr>
    </table>

    {{-- B. DATA PEKERJAAN --}}
    <div class="section-title">b. &nbsp; Data Pekerjaan Sekarang</div>

    <table class="form-table">
        <tr>
            <td class="lbl">Nama Institusi / Perusahaan</td>
            <td class="colon">:</td>
            <td class="val">{{ $instansi }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Jabatan</td>
            <td class="colon">:</td>
            <td class="val">{{ $pekerjaan }}</td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        <tr>
            <td class="lbl">Alamat Kantor</td>
            <td class="colon">:</td>
            <td class="val" style="vertical-align:top; padding-top:3px;">{{ $alamat_instansi ?? '' }}</td>
        </tr>
        {{-- Baris 2 alamat kantor + kode pos --}}
        <tr>
            <td class="lbl"></td>
            <td class="colon"></td>
            <td style="vertical-align:bottom; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:52%; border-bottom:2px solid #000; padding-bottom:2px;"></td>
                        <td style="width:2%;"></td>
                        <td
                            style="width:18%; font-size:9pt; vertical-align:bottom; padding-bottom:3px; white-space:nowrap;">
                            Kode pos :</td>
                        <td style="width:28%; border-bottom:2px solid #000; padding-bottom:2px;">
                            {{ $kode_pos_kantor }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="spacer">
            <td colspan="3"></td>
        </tr>

        {{-- No Telp Kantor --}}
        <tr>
            <td class="lbl">No. Telp/Fax/E-mail</td>
            <td class="colon">:</td>
            <td style="vertical-align:bottom; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">Telp :
                        </td>
                        <td style="width:35%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                        <td
                            style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">
                            Fax :</td>
                        <td style="width:35%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                    </tr>
                    <tr style="height:5px;"></tr>
                    <tr>
                        <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">E-mail
                            :</td>
                        <td style="width:50%; border-bottom:1px solid #000; padding-bottom:2px;">{{ $email }}</td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ============================================================ --}}
    {{-- BAGIAN 2: DATA SERTIFIKASI (halaman baru) --}}
    {{-- ============================================================ --}}
    <div class="page-break"></div>

    <div class="bagian-heading">Bagian 2 : Data Sertifikasi</div>
    <div class="bagian-desc">
        Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada
        skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta
        pengalaman kerja yang anda miliki.
    </div>

    {{-- Tabel Skema + Tujuan Asesmen --}}
    <table class="tbl-border" style="margin-bottom:14px;">
        <tr>
            <td rowspan="2" style="width:35%;">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td style="width:12%;">Judul</td>
            <td style="width:3%; text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $kode_skema }}</td>
        </tr>
        <tr>
            <td>Tujuan Asesmen</td>
            <td style="text-align:center;">:</td>
            <td colspan="2">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 4px; border: 0px solid #000;">
                            <span
                                class="{{ ($tujuan_asesmen ?? '') == 'sertifikasi' ? 'checkbox-checked' : 'checkbox' }}"></span>
                            Sertifikasi
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:6px 4px; border: 0px solid #000;">
                            <span
                                class="{{ ($tujuan_asesmen ?? '') == 'pengakuan_kompetensi_terkini' ? 'checkbox-checked' : 'checkbox' }}"></span>
                            Pengakuan Kompetensi Terkini (PKT)
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:6px 4px; border: 0px solid #000;">
                            <span
                                class="{{ ($tujuan_asesmen ?? '') == 'rekognisi_pembelajaran_lampau' ? 'checkbox-checked' : 'checkbox' }}"></span>
                            Rekognisi Pembelajaran Lampau (RPL)
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:6px 4px; border: 0px solid #000;">
                            <span
                                class="{{ ($tujuan_asesmen ?? '') == 'lainnya' ? 'checkbox-checked' : 'checkbox' }}"></span>
                            Lainnya
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Daftar Unit Kompetensi --}}
    <div style="font-size:9.5pt; font-weight:bold; margin-bottom:6px;">Daftar Unit Kompetensi sesuai kemasan:</div>
    <table class="tbl-border">
        <thead>
            <tr>
                <th style="width:8%;">No</th>
                <th style="width:28%;">Kode Unit</th>
                <th>Judul Unit Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($unit_kompetensi))
                @foreach($unit_kompetensi as $i => $unit)
                    <tr>
                        <td class="center">{{ $i + 1 }}.</td>
                        <td>{{ $unit['kode'] }}</td>
                        <td>{{ $unit['judul'] }}</td>
                    </tr>
                @endforeach
            @else
                @for($i = 1; $i <= 9; $i++)
                    <tr>
                        <td class="center">{{ $i }}.</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    {{-- ============================================================ --}}
    {{-- BAGIAN 3: BUKTI KELENGKAPAN (halaman baru) --}}
    {{-- ============================================================ --}}
    <div class="page-break"></div>

    <div class="bagian-heading">Bagian 3 : Bukti Kelengkapan Pemohon</div>

    {{-- 3.1 Bukti Persyaratan Dasar --}}
    <div style="font-size:9.5pt; font-weight:bold; margin: 6px 0 6px 0;">3.1 Bukti Persyaratan Dasar Pemohon</div>
    <table class="tbl-border">
        <thead>
            <tr>
                <th rowspan="2" style="width:6%;">No.</th>
                <th rowspan="2">Bukti Persyaratan Dasar</th>
                <th colspan="2">Ada</th>
                <th rowspan="2" style="width:12%;">Tidak Ada</th>
            </tr>
            <tr>
                <th style="width:13%;">Memenuhi Syarat</th>
                <th style="width:13%;">Tidak Memenuhi Syarat</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($persyaratan_dasar))
                @foreach($persyaratan_dasar as $i => $syarat)
                    <tr>
                        <td class="center">{{ $i + 1 }}.</td>
                        <td>{{ $syarat['nama'] }}</td>
                        <td class="center">
                            <span class="{{ $syarat['status']->value === 'valid' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                        <td class="center">
                            <span class="{{ $syarat['status']->value === 'invalid' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                        <td class="center">
                            <span class="{{ $syarat['status']->value === 'pending' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="center">1.</td>
                    <td>Pendidikan minimal SMA/SMK Sederajat dan memiliki sertifikat pelatihan berbasis Kompetensi sesuai
                        bidang atau pengalaman minimal selama 2 tahun di bidang terkait.</td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"></td>
                </tr>
                <tr>
                    <td class="center">2.</td>
                    <td>Surat Pengalaman Kerja Minimal 2 Tahun di bidang terkait</td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"></td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- 3.2 Bukti Administratif --}}
    <div style="font-size:9.5pt; font-weight:bold; margin: 10px 0 6px 0;">3.2 Bukti Administratif</div>
    <table class="tbl-border">
        <thead>
            <tr>
                <th rowspan="2" style="width:6%;">No.</th>
                <th rowspan="2">Bukti Administratif</th>
                <th colspan="2">Ada</th>
                <th rowspan="2" style="width:12%;">Tidak Ada</th>
            </tr>
            <tr>
                <th style="width:13%;">Memenuhi Syarat</th>
                <th style="width:13%;">Tidak Memenuhi Syarat</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($bukti_administratif))
                @foreach($bukti_administratif as $i => $bukti)
                    <tr>
                        <td class="center">{{ $i + 1 }}.</td>
                        <td>{{ $bukti['nama'] }}</td>
                        <td class="center">
                            <span class="{{ $bukti['status']->value === 'valid' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                        <td class="center">
                            <span class="{{ $bukti['status']->value === 'invalid' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                        <td class="center">
                            <span class="{{ $bukti['status']->value === 'pending' ? 'checkbox-checked' : 'checkbox' }}"></span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="center">1.</td>
                    <td>Pas Foto 3x4 Background Merah</td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"></td>
                </tr>
                <tr>
                    <td class="center">2.</td>
                    <td>KTP</td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"><span class="checkbox"></span></td>
                    <td class="center"></td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- Tabel Rekomendasi + TTD --}}
    <table class="tbl-rekom">
        <tr>
            <td style="width:55%; vertical-align:top;">
                <div style="font-weight:bold; margin-bottom:4px;">Rekomendasi (diisi oleh LSP):</div>
                <div style="margin-bottom:6px;">Berdasarkan ketentuan persyaratan dasar, maka pemohon:</div>
                <div><strong>{{$status_terima}}</strong> sebagai peserta sertifikasi</div>
                <br><br>
                <div style="font-weight:bold;">Catatan :</div>
                <br><br><br>
            </td>
            <td style="width:45%; vertical-align:top; padding:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">
                            Pemohon / Kandidat :
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; width:40%;">Nama</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">{{ $nama }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; height:50px; vertical-align:top;">
                            Tanda tangan/<br>Tanggal
                        </td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;"></td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">
                            Admin LSP :
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;">Nama :</td>
                        <td style="border-bottom:1px solid #000; padding:4px 6px;"></td>
                    </tr>
                    <tr>
                        <td style="padding:4px 6px; height:50px; vertical-align:top;">
                            Tanda tangan/<br>Tanggal
                        </td>
                        <td style="padding:4px 6px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>