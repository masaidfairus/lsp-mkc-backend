
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.AK.03 - Umpan Balik dan Catatan Asesmen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
        }

        .page {
            width: 100%;
        }

        .form-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        .header-table .val { font-weight: bold; }

        .instruksi {
            font-size: 9pt;
            margin-bottom: 8px;
        }

        /* Tabel Komponen */
        .komponen-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .komponen-table th,
        .komponen-table td {
            border: 1px solid #000;
            font-size: 9pt;
            padding: 5px 7px;
            vertical-align: middle;
        }
        .komponen-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }

        .col-komponen { width: 62%; text-align: left; }
        .col-ya       { width: 7%;  text-align: center; }
        .col-tidak    { width: 7%;  text-align: center; }
        .col-catatan  { width: 24%; text-align: left; vertical-align: top; }

        .komponen-row td { height: 32px; vertical-align: middle; }
        .catatan-row td  { height: 60px; vertical-align: top; }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
        }

        /* TTD */
        .ttd-table {
            width: 55%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .ttd-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: top;
        }
        .ttd-table .section-header td {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .ttd-table .ttd-row td { height: 42px; }
    </style>
</head>
<body>
<div class="page">

    
    <div class="form-title">FR.AK.03.&nbsp; UMPAN BALIK DAN CATATAN ASESMEN</div>

    
    <table class="header-table">
        <tr>
            <td style="width:26%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
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
            <td rowspan="2">Tanggal Asesmen</td>
            <td>Mulai</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($tanggal_mulai); ?></td>
        </tr>
        <tr>
            <td>Selesai</td>
            <td style="text-align:center">:</td>
            <td><?php echo e($tanggal_selesai); ?></td>
        </tr>
    </table>

    
    <div class="instruksi">Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan):</div>

    
    <table class="komponen-table">
        <thead>
            <tr>
                <th class="col-komponen">KOMPONEN</th>
                <th colspan="2">Hasil</th>
                <th class="col-catatan">Catatan/Komentar<br>Asesi</th>
            </tr>
            <tr>
                <th class="col-komponen" style="background:#fff; font-weight:normal"></th>
                <th class="col-ya">Ya</th>
                <th class="col-tidak">Tidak</th>
                <th class="col-catatan" style="background:#fff; font-weight:normal"></th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $komponen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="komponen-row">
                <td class="col-komponen"><?php echo e($item); ?></td>
                <td class="col-ya"><span class="checkbox"></span></td>
                <td class="col-tidak"><span class="checkbox"></span></td>
                <td class="col-catatan"></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

            
            <tr class="catatan-row">
                <td colspan="4" style="vertical-align:top; padding: 5px 7px;">
                    <strong>Catatan/komentar lainnya (apabila ada) :</strong><br><br>
                    <?php echo e($catatan_lainnya); ?>

                </td>
            </tr>
        </tbody>
    </table>

</div>
</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/AK/ak03.blade.php ENDPATH**/ ?>