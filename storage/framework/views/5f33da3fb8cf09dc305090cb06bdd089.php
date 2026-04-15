
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.VA - Memberikan Kontribusi dalam Validasi Asesmen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
        }

        /* Logo + Judul */
        .header-top {
            margin-bottom: 8px;
        }
        .logo-bnsp {
            font-weight: bold;
            font-size: 10pt;
            color: #c0392b;
            border: 2px solid #c0392b;
            display: inline-block;
            padding: 2px 6px;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        .form-title {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        /* Header identitas */
        .ident-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .ident-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        .ident-table .val { font-weight: bold; }

        /* Section header (orange) */
        .section-header {
            background-color: #e8742a;
            color: #fff;
            font-weight: bold;
            padding: 5px 8px;
            font-size: 9.5pt;
            margin-bottom: 0;
        }

        /* Checkbox */
        .checkbox {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            margin-right: 4px;
            flex-shrink: 0;
        }

        /* ── Tabel Tujuan/Konteks/Pendekatan ── */
        .tfp-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .tfp-table th, .tfp-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .tfp-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
            text-transform: uppercase;
        }
        .tfp-table .col-a { width: 33%; }
        .tfp-table .col-b { width: 34%; }
        .tfp-table .col-c { width: 33%; }
        .tfp-table .item-row td { padding: 3px 6px; height: 28px; vertical-align: middle; }

        /* ── Tabel Orang Relevan ── */
        .orang-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .orang-table th, .orang-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .orang-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        .orang-table .col-orang  { width: 28%; }
        .orang-table .col-nama   { width: 28%; }
        .orang-table .col-hasil  { width: 44%; background-color: #d9d9d9; font-size: 8pt; }
        .orang-table .data-row td { height: 28px; vertical-align: middle; }

        /* ── Tabel Acuan + Dokumen ── */
        .acuan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .acuan-table th, .acuan-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            vertical-align: middle;
        }
        .acuan-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        .acuan-table .col-a { width: 50%; }
        .acuan-table .col-b { width: 50%; }
        .acuan-table .item-row td { height: 24px; }

        /* ── Bagian 2 ── */
        .komunikasi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .komunikasi-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .komunikasi-table .col-label { width: 40%; }
        .komunikasi-table .col-opts  { width: 60%; }

        /* ── Tabel Aspek Validasi ── */
        .aspek-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .aspek-table th, .aspek-table td {
            border: 1px solid #000;
            padding: 4px 5px;
            font-size: 8pt;
            text-align: center;
            vertical-align: middle;
        }
        .aspek-table th {
            background-color: #d9d9d9;
            font-weight: bold;
        }
        .aspek-table .col-no    { width: 5%; }
        .aspek-table .col-aspek { width: 35%; text-align: left; padding-left: 6px; }
        .aspek-table .col-check { width: 7%; }
        .aspek-table .data-row td { height: 24px; }

        .note-small { font-size: 8pt; margin-bottom: 10px; }

        /* ── Bagian 3: Temuan ── */
        .temuan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .temuan-table th, .temuan-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .temuan-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        .temuan-table .col-no    { width: 6%; text-align: center; }
        .temuan-table .col-temuan{ width: 47%; }
        .temuan-table .col-rekom { width: 47%; }
        .temuan-table .data-row td { height: 55px; vertical-align: top; }

        /* ── Rencana Implementasi ── */
        .impl-table {
            width: 100%;
            border-collapse: collapse;
        }
        .impl-table th, .impl-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 8.5pt;
            vertical-align: top;
        }
        .impl-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .impl-table .col-no    { width: 6%;  text-align: center; }
        .impl-table .col-keg   { width: 38%; }
        .impl-table .col-waktu { width: 22%; }
        .impl-table .col-pj    { width: 34%; }
        .impl-table .data-row td { height: 65px; vertical-align: top; }

        /* Orange bottom bar */
        .orange-bar {
            height: 8px;
            background-color: #e8742a;
            margin-top: 16px;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    
    <div class="header-top">
        <img src="<?php echo e(public_path('images/logo_bnsp.png')); ?>" height="40" alt="">
    </div>
    <div class="form-title">FR.VA MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN</div>

    
    <table class="ident-table">
        <tr>
            <td style="width:18%; font-weight:bold" rowspan="3">Tim Validasi</td>
            <td style="width:3%; text-align:center" rowspan="3">:</td>
            <td style="width:30%">1. <?php echo e($tim_validasi[0] ?? ''); ?></td>
            <td style="width:15%">Hari/Tanggal</td>
            <td style="width:3%; text-align:center">:</td>
            <td><?php echo e($hari_tanggal); ?></td>
        </tr>
        <tr>
            <td>2. <?php echo e($tim_validasi[1] ?? ''); ?></td>
            <td rowspan="2">Tempat</td>
            <td rowspan="2" style="text-align:center">:</td>
            <td rowspan="2"><?php echo e($tempat); ?></td>
        </tr>
        <tr>
            <td>3. <?php echo e($tim_validasi[2] ?? ''); ?></td>
        </tr>
        <tr>
            <td>Periode</td>
            <td style="text-align:center">:</td>
            <td colspan="4">
                <span class="checkbox"></span> SEBELUM ASESMEN &nbsp;
                <span class="checkbox"></span> PADA SAAT ASESMEN &nbsp;
                <span class="checkbox"></span> SETELAH ASESMEN
            </td>
        </tr>
        <tr>
            <td>Nama Skema</td>
            <td style="text-align:center">:</td>
            <td colspan="4" class="val"><?php echo e($nama_skema); ?></td>
        </tr>
        <tr>
            <td>Nomor Skema</td>
            <td style="text-align:center">:</td>
            <td colspan="4" class="val"><?php echo e($nomor_skema); ?></td>
        </tr>
    </table>

    
    <div class="section-header">1.&nbsp;&nbsp; Menyiapkan Proses Validasi</div>

    
    <table class="tfp-table">
        <thead>
            <tr>
                <th class="col-a">Tujuan dan Fokus Validasi</th>
                <th class="col-b">Konteks Validasi</th>
                <th class="col-c">Pendekatan Validasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $maxRows = max(count($tujuan_fokus), count($konteks_validasi), count($pendekatan_validasi)); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $maxRows; $i++): ?>
            <tr class="item-row">
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tujuan_fokus[$i])): ?>
                    <span class="checkbox"></span><?php echo e($tujuan_fokus[$i]['label']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($konteks_validasi[$i])): ?>
                    <span class="checkbox"></span><?php echo e($konteks_validasi[$i]['label']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pendekatan_validasi[$i])): ?>
                    <span class="checkbox"></span><?php echo e($pendekatan_validasi[$i]['label']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    
    <table class="orang-table">
        <thead>
            <tr>
                <th class="col-orang">Orang yang Relevan</th>
                <th class="col-nama">Nama</th>
                <th class="col-hasil" style="background-color:#d9d9d9">
                    Hasil Konfirmasi/diskusi<br>
                    <span style="font-weight:normal; font-size:7.5pt">[Tujuan, fokus &amp; konteks]</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $orang_relevan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="data-row">
                <td><span class="checkbox"></span><?php echo e($o['label']); ?></td>
                <td><?php echo e($o['nama']); ?></td>
                <td><?php echo e($o['hasil']); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    <div class="orange-bar"></div>

    
    <div class="page-break"></div>

<div class="header-top">
        <img src="<?php echo e(public_path('images/logo_bnsp.png')); ?>" height="40" alt="">
    </div>
    
    <table class="acuan-table">
        <thead>
            <tr>
                <th class="col-a">Acuan Pembanding :</th>
                <th class="col-b">Dokumen Terkait dan Bahan-Bahan</th>
            </tr>
        </thead>
        <tbody>
            <?php $maxAcuan = max(count($acuan_pembanding), count($dokumen_terkait)); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $maxAcuan; $i++): ?>
            <tr class="item-row">
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($acuan_pembanding[$i])): ?>
                    <span class="checkbox"></span><?php echo e($acuan_pembanding[$i]['label']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($dokumen_terkait[$i])): ?>
                    <span class="checkbox"></span><?php echo e($dokumen_terkait[$i]['label']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>

    
    <div class="section-header" style="margin-top:10px">2.&nbsp;&nbsp; Memberikan Kontribusi dalam Proses Validasi</div>

    <table class="komunikasi-table">
        <tr>
            <td class="col-label">Keterampilan komunikasi yang digunakan dalam kegiatan validasi :</td>
            <td class="col-opts">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $komunikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <span class="checkbox"></span><?php echo e($k['label']); ?><br>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </td>
        </tr>
    </table>

    
    <table class="aspek-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No.</th>
                <th class="col-aspek" rowspan="2">Aspek Dalam Kegiatan Validasi<br><span style="font-weight:normal; font-size:7.5pt">(Meninjau, Membandingkan, Mengevaluasi)</span></th>
                <th colspan="4">Aturan Bukti</th>
                <th colspan="4">Prinsip Asesmen</th>
            </tr>
            <tr>
                <th class="col-check">V</th>
                <th class="col-check">A</th>
                <th class="col-check">T</th>
                <th class="col-check">M</th>
                <th class="col-check">V</th>
                <th class="col-check">R</th>
                <th class="col-check">F</th>
                <th class="col-check">F</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $aspek_validasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="data-row">
                <td class="col-no"><?php echo e($a['no']); ?>.</td>
                <td class="col-aspek" style="text-align:left"><?php echo e($a['label']); ?></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
    <div class="note-small">☺ yang perlu</div>

    
    <div class="section-header" style="margin-top:4px">3.&nbsp;&nbsp; Memberikan Kontribusi untuk Hasil Asesmen</div>

    <table class="temuan-table">
        <thead>
            <tr>
                <th class="col-no"></th>
                <th class="col-temuan">Temuan Validasi :</th>
                <th class="col-rekom">Rekomendasi untuk meningkatkan Praktek Asesmen</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $temuan_rekomendasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="data-row">
                <td class="col-no" style="vertical-align:top; padding-top:6px"><?php echo e($t['no']); ?>.</td>
                <td><?php echo e($t['temuan']); ?></td>
                <td><?php echo e($t['rekomendasi']); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    <div class="orange-bar"></div>

    
    <div class="page-break"></div>

<div class="header-top">
        <img src="<?php echo e(public_path('images/logo_bnsp.png')); ?>" height="40" alt="">
    </div>
    
    <table class="impl-table">
        <thead>
            <tr>
                <td colspan="4" style="border:1px solid #000; padding:5px 7px; font-size:9pt;">
                    Rencana Implementasi Perubahan / Perbaikan Pelaksanaan Asesmen :
                </td>
            </tr>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-keg">Kegiatan Perbaikan Sesuai<br>Rekomendasi</th>
                <th class="col-waktu">Waktu<br>Penyelesaian</th>
                <th class="col-pj">Penanggung Jawab</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rencana_implementasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="data-row">
                <td class="col-no" style="vertical-align:top; padding-top:6px"><?php echo e($r['no']); ?>.</td>
                <td><?php echo e($r['kegiatan']); ?></td>
                <td><?php echo e($r['waktu']); ?></td>
                <td><?php echo e($r['penanggung_jawab']); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    <div class="orange-bar"></div>

</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/mkva.blade.php ENDPATH**/ ?>