<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.APL.02 - Asesmen Mandiri</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            background: #fff;
            padding: 6rem ;
        }

        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }

        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .tbl-border {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
        }
        .tbl-border td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 12px;
        }
        .panduan-box .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .panduan-box ul {
            margin-left: 16px;
        }
        .panduan-box ul li {
            margin-bottom: 4px;
            line-height: 1.4;
        }

        /*
            SATU tabel, 6 kolom:
            col1: Unit Kompetensi = 22%
            col2: Label (Kode/Judul Unit) = 14%
            col3: Titik dua = 3%
            col4: Nilai / Dapatkah Saya = 37%
            col5: K = 6%
            col6: BK = 6%
            col7: Bukti = 12%
            Total = 100%
        */
        .tbl-unit {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 14px;
            table-layout: fixed;
        }
        .tbl-unit td, .tbl-unit th {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .tbl-unit th {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .elemen-nama {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .kriteria-label {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kriteria-item {
            margin-left: 8px;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .tbl-rekom {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }
        .tbl-rekom td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

<?php
    $unit_kompetensi = $unit_kompetensi ?? [];
?>

<div class="judul-utama">FR.APL.02. ASESMEN MANDIRI</div>

<table class="tbl-border" style="margin-bottom:12px;">
    <tr>
        <td rowspan="2" style="width:35%; vertical-align:middle;">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
        <td style="width:15%;">Judul</td>
        <td style="width:3%; text-align:center;">:</td>
        <td style="font-weight:bold;"><?php echo e($nama_skema ?? ''); ?></td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;"><?php echo e($kode_skema ?? ''); ?></td>
    </tr>
</table>

<div class="panduan-box">
    <div class="panduan-title">PANDUAN ASESMEN MANDIRI</div>
    <div style="font-weight:bold; margin-bottom:4px;">Instruksi:</div>
    <ul>
        <li>Baca setiap pertanyaan di kolom sebelah kiri</li>
        <li>Beri tanda centang (&#10003;) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.</li>
        <li>Isi kolom di sebelah kanan dengan menuliskan bukti yang relevan anda miliki untuk menunjukkan bahwa anda melakukan pekerjaan.</li>
    </ul>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $unit_kompetensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->first): ?>
    <div class="page-break"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <table class="tbl-unit">
        
        <colgroup>
            <col style="width:22%;">
            <col style="width:14%;">
            <col style="width:3%;">
            <col style="width:37%;">
            <col style="width:6%;">
            <col style="width:6%;">
            <col style="width:12%;">
        </colgroup>

        
        <tr>
            <td rowspan="2" style="font-weight:bold; vertical-align:middle;">
                Unit Kompetensi <?php echo e($unit['no']); ?>

            </td>
            <td>Kode Unit</td>
            <td style="text-align:center; vertical-align:middle;">:</td>
            <td colspan="4"><?php echo e($unit['kode']); ?></td>
        </tr>

        
        <tr>
            <td>Judul Unit</td>
            <td style="text-align:center; vertical-align:middle;">:</td>
            <td colspan="4"><?php echo e($unit['judul']); ?></td>
        </tr>

        
        <tr>
            <th colspan="4" style="text-align:left;">Dapatkah Saya.............?</th>
            <th>K</th>
            <th>BK</th>
            <th>Bukti yang relevan</th>
        </tr>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $unit['elemen']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $elemen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <tr>
            <td colspan="4">
                <div class="elemen-nama">
                    <?php echo e($elemen['no']); ?>&nbsp;&nbsp;Elemen: <span style="font-weight:normal;"><?php echo e($elemen['nama']); ?></span>
                </div>
                <div class="kriteria-label">Kriteria Unjuk Kerja:</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $elemen['kriteria']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div class="kriteria-item">
                    <?php echo e($kri['no']); ?>&nbsp;&nbsp;&nbsp;<?php echo e($kri['uraian']); ?>

                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </td>
            <td style="text-align:center; vertical-align:middle;"><span class="checkbox"></span></td>
            <td style="text-align:center; vertical-align:middle;"><span class="checkbox"></span></td>
            <td></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    </table>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

<table class="tbl-rekom">
    <tr>
        <td style="width:40%;">
            <div style="font-weight:bold; margin-bottom:6px;">Rekomendasi Untuk Asesi:</div>
            <div>Asesmen dapat / tidak dapat dilanjutkan</div>
            <br>
            <div>melalui pendekatan .............................................</div>
        </td>
        <td style="width:60%; padding:0;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">Asesi :</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px; width:35%;">Nama</td>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;"><?php echo e($nama_asesi ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px; height:45px; vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px; font-weight:bold;" colspan="2">Ditinjau Oleh Asesor :</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;">Nama :</td>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;"><?php echo e($nama_asesor ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;">No. Reg:</td>
                    <td style="border-bottom:1px solid #000; padding:4px 6px;"><?php echo e($no_reg_asesor ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="padding:4px 6px; height:45px; vertical-align:top;">Tanda tangan/<br>Tanggal</td>
                    <td style="padding:4px 6px;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/APL/apl02.blade.php ENDPATH**/ ?>