<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.02</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
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
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 9pt;
            line-height: 1.4;
        }

        .tbl-kp {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 9pt;
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

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin: 10px 0 6px 0;
        }

        .petunjuk-list {
            padding-left: 20px;
            margin-bottom: 12px;
        }
        .petunjuk-list li {
            margin-bottom: 3px;
            font-size: 9pt;
            line-height: 1.4;
        }

        .field-line {
            font-size: 9pt;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .field-space {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #000;
            margin-left: 6px;
            vertical-align: bottom;
        }

        .perlengkapan-list {
            padding-left: 22px;
            margin: 3px 0 8px 0;
        }
        .perlengkapan-list li {
            font-size: 9pt;
            margin-bottom: 2px;
        }

        /* Footer TTD */
        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
            margin-bottom: 14px;
        }
        .tbl-ttd td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }
        .tbl-ttd .header-cell {
            font-weight: bold;
            background: #f0f0f0;
        }
        .tbl-ttd .ttd-space { height: 35px; }

        /* Penyusun validator */
        .tbl-pv {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
        }
        .tbl-pv th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-pv td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            text-align: center;
        }
        .tbl-pv td.ttd-space { height: 40px; }
    </style>
</head>
<body>

<?php
    $nama_skema         = $nama_skema ?? '';
    $kode_skema         = $kode_skema ?? '';
    $tuk                = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
    $nama_asesor        = $nama_asesor ?? '';
    $nama_asesi         = $nama_asesi ?? '';
    $tanggal            = $tanggal ?? '';
    $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
    $penyusun           = $penyusun ?? [['nama'=>'','nomor_met'=>''],['nama'=>'','nomor_met'=>'']];
    $validator          = $validator ?? [['nama'=>'','nomor_met'=>''],['nama'=>'','nomor_met'=>'']];
?>


<div class="judul">FR.IA.02. &nbsp;&nbsp; TPD – TUGAS PRAKTIK DEMONSTRASI</div>


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
        <td style="font-weight:bold;" colspan="4"><?php echo e($nama_skema); ?></td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="4"><?php echo e($kode_skema); ?></td>
    </tr>
    <tr>
        <td colspan="4">TUK</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($tuk); ?></td>
    </tr>
    <tr>
        <td colspan="4">Nama Asesor</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($nama_asesor); ?></td>
    </tr>
    <tr>
        <td colspan="4">Nama Asesi</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($nama_asesi); ?></td>
    </tr>
    <tr>
        <td colspan="4">Tanggal</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($tanggal); ?></td>
    </tr>
</table>


<div class="section-title">A. &nbsp; Petunjuk</div>
<ol class="petunjuk-list">
    <li>Baca dan pelajari setiap instruksi kerja di bawah ini dengan cermat sebelum melaksanakan praktek</li>
    <li>Klarifikasi kepada asesor kompetensi apabila ada hal-hal yang belum jelas</li>
    <li><span style="text-decoration:underline;">Laksanakan</span> pekerjaan sesuai dengan urutan proses yang sudah ditetapkan</li>
    <li>Seluruh proses kerja mengacu kepada SOP/WI yang dipersyaratkan (Jika Ada)</li>
</ol>


<div class="section-title">B. &nbsp; Skenario Tugas <span style="text-decoration:underline;">Praktik</span> Demonstrasi</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kelompok_pekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->first): ?>
    <div class="page-break"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <table class="tbl-kp">
        <colgroup>
            <col style="width:18%;">
            <col style="width:5%;">
            <col style="width:20%;">
            <col style="width:57%;">
        </colgroup>
        <tr>
            <th colspan="2">Kelompok Pekerjaan <?php echo e($kp['no']); ?></th>
            <th colspan="1">No.</th>
            <th colspan="2">Kode Unit</th>
            <th colspan="3" style="text-align:left; padding-left:8px;">Judul Unit</th>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kp['unit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i === 0): ?>
            <td colspan="2" rowspan="<?php echo e(count($kp['unit'])); ?>" style="vertical-align:middle; text-align:center; font-size:8.5pt;"><?php echo e($kp['nama']); ?></td>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <td colspan="1" style="text-align:center;"><?php echo e($i + 1); ?>.</td>
            <td colspan="2"><?php echo e($unit['kode']); ?></td>
            <td colspan="3"><?php echo e($unit['judul']); ?></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </table>

    
    <div class="field-line" style="margin-bottom:80px;">
        Skenario Tugas <span style="text-decoration:underline;">Praktik</span> Demonstrasi &nbsp;&nbsp; :
    </div>

    
    <div class="field-line" style="margin-bottom:4px;">Perlengkapan dan Peralatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</div>
    <ol class="perlengkapan-list">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($kp['perlengkapan'])): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kp['perlengkapan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <li><?php echo e($item); ?></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <?php else: ?>
            <li>A</li>
            <li>B</li>
            <li>C</li>
            <li>Dst</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </ol>

    
    <div class="field-line" style="margin-bottom:80px;">
        Waktu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
    </div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>


<div class="page-break"></div>

<table class="tbl-ttd" style="margin-bottom:6px;">
    <colgroup>
        <col style="width:30%;">
        <col style="width:4%;">
        <col style="width:66%;">
    </colgroup>
    <tr>
        <td colspan="7" class="header-cell">ASESI :</td>
    </tr>
    <tr>
        <td colspan="2">Nama</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($nama_asesi); ?></td>
    </tr>
    <tr>
        <td class="ttd-space" colspan="2">Tanda tangan dan Tanggal</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="7" class="header-cell">ASESOR :</td>
    </tr>
    <tr>
        <td colspan="2">Nama</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"><?php echo e($nama_asesor); ?></td>
    </tr>
    <tr>
        <td colspan="2">No. Reg</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td class="ttd-space" colspan="2">Tanda tangan dan Tanggal</td>
        <td style="text-align:center;">:</td>
        <td colspan="4"></td>
    </tr>
</table>

<div class="section-title" style="margin-top:14px;">PENYUSUN DAN VALIDATOR</div>
<table class="tbl-pv">
    <colgroup>
        <col style="width:18%;">
        <col style="width:7%;">
        <col style="width:35%;">
        <col style="width:25%;">
        <col style="width:15%;">
    </colgroup>
    <tr>
        <th colspan="2">STATUS</th>
        <th colspan="1">NO</th>
        <th colspan="2">NAMA</th>
        <th colspan="2">NOMOR MET</th>
        <th colspan="2">TANDA TANGAN DAN TANGGAL</th>
    </tr>
    <tr>
        <td colspan="2" rowspan="2" style="vertical-align:middle; font-weight:bold;">PENYUSUN</td>
        <td colspan="1">1</td>
        <td colspan="2"><?php echo e($penyusun[0]['nama'] ?? ''); ?></td>
        <td colspan="2"><?php echo e($penyusun[0]['nomor_met'] ?? ''); ?></td>
        <td colspan="2" class="ttd-space"></td>
    </tr>
    <tr>
        <td>2</td>
        <td colspan="2"><?php echo e($penyusun[1]['nama'] ?? ''); ?></td>
        <td colspan="2"><?php echo e($penyusun[1]['nomor_met'] ?? ''); ?></td>
        <td colspan="2" class="ttd-space"></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2" style="vertical-align:middle; font-weight:bold;">VALIDATOR</td>
        <td>1</td>
        <td colspan="2"><?php echo e($validator[0]['nama'] ?? ''); ?></td>
        <td colspan="2"><?php echo e($validator[0]['nomor_met'] ?? ''); ?></td>
        <td colspan="2" class="ttd-space"></td>
    </tr>
    <tr>
        <td>2</td>
        <td colspan="2"><?php echo e($validator[1]['nama'] ?? ''); ?></td>
        <td colspan="2"><?php echo e($validator[1]['nomor_met'] ?? ''); ?></td>
        <td colspan="2" class="ttd-space"></td>
    </tr>
</table>

</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/IA/ia02.blade.php ENDPATH**/ ?>