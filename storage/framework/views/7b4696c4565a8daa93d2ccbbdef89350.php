<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.APL.01 - Permohonan Sertifikasi Kompetensi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
        .form-table tr.spacer td { height: 5px; border: none; }

        /* ===== CHECKBOX ===== */
        .checkbox {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
        }
        .checkbox-checked {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
            background: #000;
        }

        /* ===== TABEL BORDER (Bagian 2 & 3) ===== */
        .tbl-border {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
        }
        .tbl-border td, .tbl-border th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .tbl-border th {
            font-weight: bold;
            text-align: center;
        }
        .tbl-border td.center { text-align: center; }

        /* ===== PAGE BREAK ===== */
        .page-break { page-break-before: always; }

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

    <?php
    $unit_kompetensi     = $unit_kompetensi ?? [];
    $persyaratan_dasar   = $persyaratan_dasar ?? [];
    $bukti_administratif = $bukti_administratif ?? [];
    ?>



<div class="judul-utama">FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI</div>




<div class="bagian-heading">Bagian 1 : Rincian Data Pemohon Sertifikasi</div>
<div class="bagian-desc">
    Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.
</div>


<div class="section-title">a. &nbsp; Data Pribadi</div>

<table class="form-table">
    <tr>
        <td class="lbl">Nama lengkap</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($nama); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">No. KTP/NIK/Paspor</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($nik); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Tempat / tgl. Lahir</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($tempat_lahir); ?>,&nbsp; <?php echo e($tanggal_lahir); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Jenis kelamin</td>
        <td class="colon">:</td>
        <td class="val">
            <?php echo e($jenis_kelamin); ?>

        </td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Kebangsaan</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($kebangsaan ?? 'Indonesia'); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    
    <tr>
        <td class="lbl">Alamat rumah</td>
        <td class="colon"></td>
        <td class="val"><?php echo e($alamat); ?></td>
    </tr>
    
    <tr>
        <td class="lbl"></td>
        <td class="colon"></td>
        <td class="val"><?php echo e($rt_rw ? 'RT/RW '.$rt_rw.', ' : ''); ?><?php echo e($kelurahan); ?>, <?php echo e($kecamatan); ?></td>
    </tr>
    
    <tr>
        <td class="lbl"></td>
        <td class="colon">:</td>
        <td style="vertical-align:bottom; padding-bottom:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:52%; border-bottom:2px solid #000; padding-bottom:2px; font-size:9.5pt;">
                        <?php echo e($kabupaten); ?>, <?php echo e($provinsi); ?>

                    </td>
                    <td style="width:2%;"></td>
                    <td style="width:18%; font-size:9pt; vertical-align:bottom; padding-bottom:3px; white-space:nowrap;">Kode pos :</td>
                    <td style="width:28%; border-bottom:2px solid #000; padding-bottom:2px; text-align:center; font-size:9.5pt;">
                        <?php echo e($kode_pos); ?>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    
    <tr>
        <td class="lbl">No. Telepon/E-mail</td>
        <td class="colon">:</td>
        <td style="vertical-align:bottom; padding-bottom:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">Rumah :</td>
                    <td style="width:32%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">Kantor :</td>
                    <td style="width:32%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                </tr>
                <tr style="height:5px;"></tr>
                <tr>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">HP :</td>
                    <td style="border-bottom:1px solid #000; padding-bottom:2px; font-size:9.5pt;"><?php echo e($no_telp); ?></td>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">E-mail :</td>
                    <td style="border-bottom:1px solid #000; padding-bottom:2px; font-size:9.5pt;"><?php echo e($email); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Kualifikasi Pendidikan</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($pendidikan); ?></td>
    </tr>
</table>


<div class="section-title">b. &nbsp; Data Pekerjaan Sekarang</div>

<table class="form-table">
    <tr>
        <td class="lbl">Nama Institusi / Perusahaan</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($instansi); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Jabatan</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($pekerjaan); ?></td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    <tr>
        <td class="lbl">Alamat Kantor</td>
        <td class="colon">:</td>
        <td class="val"><?php echo e($alamat_instansi ?? ''); ?></td>
    </tr>
    
    <tr>
        <td class="lbl"></td>
        <td class="colon"></td>
        <td style="vertical-align:bottom; padding-bottom:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:52%; border-bottom:2px solid #000; padding-bottom:2px;"></td>
                    <td style="width:2%;"></td>
                    <td style="width:18%; font-size:9pt; vertical-align:bottom; padding-bottom:3px; white-space:nowrap;">Kode pos :</td>
                    <td style="width:28%; border-bottom:2px solid #000; padding-bottom:2px;"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="spacer"><td colspan="3"></td></tr>

    
    <tr>
        <td class="lbl">No. Telp/Fax/E-mail</td>
        <td class="colon">:</td>
        <td style="vertical-align:bottom; padding-bottom:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">Telp :</td>
                    <td style="width:35%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px; padding-left:8px;">Fax :</td>
                    <td style="width:35%; border-bottom:1px solid #000; padding-bottom:2px;"></td>
                </tr>
                <tr style="height:5px;"></tr>
                <tr>
                    <td style="font-size:9pt; white-space:nowrap; vertical-align:bottom; padding-bottom:3px;">E-mail :</td>
                    <td style="width:50%; border-bottom:1px solid #000; padding-bottom:2px;"><?php echo e($email); ?></td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>




<div class="page-break"></div>

<div class="bagian-heading">Bagian 2 : Data Sertifikasi</div>
<div class="bagian-desc">
    Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.
</div>


<table class="tbl-border" style="margin-bottom:14px;">
    <tr>
        <td rowspan="2" style="width:35%;">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
        <td style="width:12%;">Judul</td>
        <td style="width:3%; text-align:center;">:</td>
        <td style="font-weight:bold;"><?php echo e($nama_skema); ?></td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;"><?php echo e($kode_skema); ?></td>
    </tr>
    <tr>
        <td>Tujuan Asesmen</td>
        <td style="text-align:center;">:</td>
        <td colspan="2">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="padding:6px 4px; border: 0px solid #000;">
                        <span class="<?php echo e(($tujuan_asesmen ?? '') == 'Sertifikasi' ? 'checkbox-checked' : 'checkbox'); ?>"></span>
                        Sertifikasi
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 4px; border: 0px solid #000;">
                        <span class="<?php echo e(($tujuan_asesmen ?? '') == 'PKT' ? 'checkbox-checked' : 'checkbox'); ?>"></span>
                        Pengakuan Kompetensi Terkini (PKT)
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 4px; border: 0px solid #000;">
                        <span class="<?php echo e(($tujuan_asesmen ?? '') == 'RPL' ? 'checkbox-checked' : 'checkbox'); ?>"></span>
                        Rekognisi Pembelajaran Lampau (RPL)
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 4px; border: 0px solid #000;">
                        <span class="<?php echo e(($tujuan_asesmen ?? '') == 'Lainnya' ? 'checkbox-checked' : 'checkbox'); ?>"></span>
                        Lainnya
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($unit_kompetensi)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $unit_kompetensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td class="center"><?php echo e($i + 1); ?>.</td>
                <td><?php echo e($unit['kode']); ?></td>
                <td><?php echo e($unit['judul']); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <?php else: ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 9; $i++): ?>
            <tr>
                <td class="center"><?php echo e($i); ?>.</td>
                <td></td>
                <td></td>
            </tr>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>




<div class="page-break"></div>

<div class="bagian-heading">Bagian 3 : Bukti Kelengkapan Pemohon</div>


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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($persyaratan_dasar)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $persyaratan_dasar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $syarat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td class="center"><?php echo e($i + 1); ?>.</td>
                <td><?php echo e($syarat); ?></td>
                <td class="center"><span class="checkbox"></span></td>
                <td class="center"><span class="checkbox"></span></td>
                <td class="center"></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <?php else: ?>
        <tr>
            <td class="center">1.</td>
            <td>Pendidikan minimal SMA/SMK Sederajat dan memiliki sertifikat pelatihan berbasis Kompetensi sesuai bidang atau pengalaman minimal selama 2 tahun di bidang terkait.</td>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>


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
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($bukti_administratif)): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $bukti_administratif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bukti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td class="center"><?php echo e($i + 1); ?>.</td>
                <td><?php echo e($bukti); ?></td>
                <td class="center"><span class="checkbox"></span></td>
                <td class="center"><span class="checkbox"></span></td>
                <td class="center"></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <?php else: ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>


<table class="tbl-rekom">
    <tr>
        <td style="width:55%; vertical-align:top;">
            <div style="font-weight:bold; margin-bottom:4px;">Rekomendasi (diisi oleh LSP):</div>
            <div style="margin-bottom:6px;">Berdasarkan ketentuan persyaratan dasar, maka pemohon:</div>
            <div><strong><?php echo e($status_terima); ?></strong> sebagai peserta sertifikasi</div>
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
                    <td style="border-bottom:1px solid #000; padding:4px 6px;"><?php echo e($nama); ?></td>
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
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/APL/apl01.blade.php ENDPATH**/ ?>