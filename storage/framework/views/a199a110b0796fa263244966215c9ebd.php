
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.04B - Penilaian Proyek Singkat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
            padding: 4rem
        }

        .page {
            width: 100%;
        }

        /* Judul */
        .form-title {
            font-weight: bold;
            font-size: 11pt;
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

        /* Aspek Penilaian table */
        .aspek-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .aspek-table th, .aspek-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 9.5pt;
            vertical-align: top;
        }
        .aspek-table th {
            font-weight: bold;
            text-align: center;
            background-color: #e8e8e8;
        }

        .col-no        { width: 5%;  text-align: center; vertical-align: middle; }
        .col-lingkup   { width: 28%; }
        .col-pertanyaan{ width: 30%; }
        .col-kesesuaian{ width: 25%; }
        .col-ya        { width: 6%;  text-align: center; vertical-align: middle; }
        .col-tdk       { width: 6%;  text-align: center; vertical-align: middle; }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
        }

        .row-data td {
            min-height: 45px;
            height: 45px;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
            vertical-align: top;
            width: 33.33%;
            height: 70px;
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
            height: 40px;
        }
    </style>
</head>
<body>
<div class="page">

    
    <div class="form-title">
        FR.IA.04B.&nbsp; PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LAINNYA
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
            <li>Lakukan penilaian pencapaian hasil proyek singkat atau kegiatan terstruktur lainnya melalui presentasi.</li>
            <li>Penilaian dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dapat pula dilakukan untuk masing-masing kelompok pekerjaan.</li>
            <li>Pertanyaan disampaikan oleh asesor pada saat asesi melakukan presentasi proyek singkat/ kegiatan terstruktur lainnya.</li>
            <li>Pertanyaan yang disampaikan untuk pemenuhan pencapaian 5 dimensi kompetensi.</li>
            <li>Isilah kolom lingkup penyajian proyek atau kegiatan terstruktur lainnya sesuai sektor/ sub-sektor/ profesi.</li>
            <li>Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.</li>
        </ul>
    </div>

    
    <table class="aspek-table">
        <thead>
            <tr>
                <th colspan="4">Aspek Penilaian</th>
                <th colspan="2">Pencapaian</th>
            </tr>
            <tr>
                <th class="col-no"></th>
                <th class="col-lingkup">Lingkup Penyajian proyek atau<br>kegiatan terstruktur lainnya</th>
                <th class="col-pertanyaan">Daftar Pertanyaan</th>
                <th class="col-kesesuaian">Kesesuaian dengan standar<br>kompetensi kerja</th>
                <th class="col-ya">Ya</th>
                <th class="col-tdk">Tdk</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $aspek_penilaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $aspek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="row-data">
                <td class="col-no"><?php echo e($aspek['lingkup'] === 'dst.' ? 'dst.' : ($i + 1) . '.'); ?></td>
                <td class="col-lingkup" style="white-space: pre-line"><?php echo e($aspek['lingkup'] === 'dst.' ? '' : $aspek['lingkup']); ?></td>
                <td class="col-pertanyaan" style="white-space: pre-line"><?php echo e($aspek['pertanyaan']); ?></td>
                <td class="col-kesesuaian" style="white-space: pre-line"><?php echo e($aspek['kesesuaian']); ?></td>
                <td class="col-ya"><span class="checkbox"></span></td>
                <td class="col-tdk"><span class="checkbox"></span></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    
    <table class="ttd-table">
        <tr>
            <td style="vertical-align:top">Tanda Tangan Asesi</td>
            <td style="vertical-align:top">Tanda Tangan Asesor</td>
            <td style="vertical-align:top">Nama &amp; Tanda Tangan<br>Supervisor (Jika ada)</td>
        </tr>
    </table>

    
    <table class="header-table" style="margin-bottom: 16px;">
        <tr>
            <td style="width:28%; font-weight:bold">Rekomendasi Asesor:</td>
            <td>
                Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:<br>
                <span class="checkbox"></span> &nbsp;Kompeten<br>
                <span class="checkbox"></span> &nbsp;Belum Kompeten
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold; background-color:#e8e8e8">Asesi :</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="border-left:1px solid #000">: <?php echo e($asesi_nama ?? ''); ?></td>
        </tr>
        <tr>
            <td style="height:45px">Tanda tangan/Tanggal</td>
            <td style="border-left:1px solid #000">: <?php echo e($asesi_ttd_tanggal ?? ''); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold; background-color:#e8e8e8">Asesor :</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="border-left:1px solid #000">: <?php echo e($asesor_nama ?? ''); ?></td>
        </tr>
        <tr>
            <td>No. Reg</td>
            <td style="border-left:1px solid #000">: <?php echo e($asesor_no_reg ?? ''); ?></td>
        </tr>
        <tr>
            <td style="height:45px">Tanda tangan/Tanggal</td>
            <td style="border-left:1px solid #000">: <?php echo e($asesor_ttd_tanggal ?? ''); ?></td>
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
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/IA/ia04b.blade.php ENDPATH**/ ?>