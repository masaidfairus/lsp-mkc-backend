
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.04A - Daftar Instruksi Terstruktur</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
            padding: 4rem;
        }

        .page {
            width: 100%;
        }

        /* Judul */
        .form-title {
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            line-height: 1.4;
        }

        /* Header table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .header-table .val { font-weight: bold; }

        .note-small {
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        /* Panduan */
        .panduan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 14px;
        }
        .panduan-box .panduan-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        .panduan-box ul {
            padding-left: 16px;
        }
        .panduan-box ul li {
            margin-bottom: 4px;
            font-size: 9pt;
            text-align: justify;
        }

        /* Kelompok section */
        .kelompok-section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        /* Unit table */
        .unit-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .unit-table th, .unit-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .unit-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }

        /* Instruksi table */
        .instruksi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .instruksi-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .instruksi-table .label-col {
            width: 32%;
            vertical-align: top;
        }
        .instruksi-table .value-col {
            width: 68%;
        }
        .instruksi-table .waktu-row {
            padding-top: 30px;
        }
        .instruksi-table .umpan-row td {
            min-height: 50px;
            height: 50px;
        }

        .catatan-footer {
            font-size: 8.5pt;
            margin-top: 4px;
            margin-bottom: 14px;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            width: 33.33%;
            min-height: 60px;
            height: 100px;
        }

        /* Penyusun & Validator */
        .pv-title {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .pv-table {
            width: 100%;
            border-collapse: collapse;
        }
        .pv-table th, .pv-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9.5pt;
            vertical-align: middle;
            text-align: center;
        }
        .pv-table th {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .pv-table .status-cell {
            font-weight: bold;
        }
        .pv-table .ttd-cell {
            min-height: 40px;
            height: 40px;
        }
    </style>
</head>
<body>
<div class="page">

    
    <div class="form-title">
        FR.IA.04A. DIT – DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK<br>
        SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)
    </div>

    
    <table class="header-table">
        <tr>
            <td style="width:28%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td style="width:10%">Judul</td>
            <td style="width:3%; text-align:center">:</td>
            <td class="val"><?php echo e($judul); ?></td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center">:</td>
            <td class="val"><?php echo e($nomor); ?></td>
        </tr>
        <tr>
            <td colspan="2">TUK</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($tuk); ?></td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesor</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($nama_asesor); ?></td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesi</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($nama_asesi); ?></td>
        </tr>
        <tr>
            <td colspan="2">Tanggal</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($tanggal); ?></td>
        </tr>
    </table>
    
    <div class="panduan-box">
        <div class="panduan-title">PANDUAN BAGI ASESOR</div>
        <ul>
            <li>Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</li>
            <li>Proyek singkat atau kegiatan terstruktur lainnya dibuat untuk keseluruhan unit kompetensi dalam Skema Sertifikasi atau untuk masing-masing kelompok pekerjaan.</li>
            <li>Kumpulkan hasil proyek singkat atau kegiatan terstruktur lainnya sesuai dengan hasil keluaran yang telah ditetapkan.</li>
        </ul>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kelompok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <div class="kelompok-section">

        
        <table class="unit-table">
            <thead>
                <tr>
                    <th style="width:22%">Kelompok<br>Pekerjaan <?php echo e($kp['no']); ?></th>
                    <th style="width:6%">No.</th>
                    <th style="width:22%">Kode Unit</th>
                    <th>Judul Unit</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kp['units']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i === 0): ?>
                    <td rowspan="<?php echo e(count($kp['units'])); ?>" style="vertical-align:middle">
                        <?php echo e($kp['nama']); ?>

                    </td>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <td style="text-align:center"><?php echo e($unit['no']); ?>.</td>
                    <td><?php echo e($unit['kode']); ?></td>
                    <td><?php echo e($unit['judul']); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </tbody>
        </table>

        
        <table class="instruksi-table">
            <tr>
                <td class="label-col">
                    Hal yang harus disiapkan atau dilakukan atau dihasilkan untuk suatu proyek singkat/ kegiatan terstruktur
                </td>
                <td class="value-col">
                    Skenario studi kasus/kegiatan terstruktur yang berisikan data informasi,
                    lingkup bahasan dan instruksi untuk asesi:<br><br>
                    <?php echo e($kp['skenario']); ?>

                    <br><br><br>
                    Waktu : <?php echo e($kp['waktu_skenario']); ?>

                </td>
            </tr>
            <tr>
                <td class="label-col">Hal yang perlu didemonstrasikan</td>
                <td class="value-col">
                    Hasil studi kasus atau kegiatan terstruktur:<br><br>
                    <?php echo e($kp['hasil']); ?>

                    <br><br><br>
                    Waktu : <?php echo e($kp['waktu_hasil']); ?>

                </td>
            </tr>
            <tr class="umpan-row">
                <td colspan="2">
                    Umpan balik untuk Asesi:<br><br>
                    <?php echo e($kp['umpan_balik']); ?>

                </td>
            </tr>
        </table>

        <div class="catatan-footer">
            *) Apabila asesi pada Level 4 ke atas, berikan tugas proyek yang meliputi tentang pemecahan masalah dan analisa
        </div>

    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    
<table class="ttd-table">
    <tr>
        <td style="width:33.33%; height:140px; vertical-align:top">Tanda Tangan Asesi</td>
        <td style="width:33.33%; height:140px; vertical-align:top">Tanda Tangan Asesor</td>
        <td style="width:33.33%; height:140px; vertical-align:top">Nama &amp; Tanda Tangan<br>Supervisor (Jika ada)</td>
    </tr>
</table>

    
    <div class="pv-title">PENYUSUN DAN VALIDATOR</div>
    <table class="pv-table">
        <thead>
            <tr>
                <th style="width:15%">STATUS</th>
                <th style="width:8%">NO</th>
                <th style="width:35%">NAMA</th>
                <th style="width:20%">NOMOR MET</th>
                <th style="width:22%">TANDA TANGAN<br>DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $penyusun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i === 0): ?>
                <td class="status-cell" rowspan="<?php echo e(count($penyusun)); ?>">PENYUSUN</td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($p['nama']); ?></td>
                <td><?php echo e($p['nomor_met']); ?></td>
                <td class="ttd-cell"></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $validator; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i === 0): ?>
                <td class="status-cell" rowspan="<?php echo e(count($validator)); ?>">VALIDATOR</td>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($v['nama']); ?></td>
                <td><?php echo e($v['nomor_met']); ?></td>
                <td class="ttd-cell"></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/IA/ia04a.blade.php ENDPATH**/ ?>