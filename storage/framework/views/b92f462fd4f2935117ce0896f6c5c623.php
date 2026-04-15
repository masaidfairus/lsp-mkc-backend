<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice Sertifikasi LSP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem
        }

        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
            size: A4 portrait;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .logo-cell {
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .logo-box {
            width: 70px;
            height: 70px;
            border: 1.5px solid #000;
            display: inline-block;
            line-height: 70px;
            font-size: 7pt;
            text-align: center;
            color: #666;
        }
        .lsp-info {
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }
        .lsp-name {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .lsp-sub {
            font-size: 8.5pt;
            margin-top: 2px;
        }
        .lsp-alamat {
            font-size: 7.5pt;
            margin-top: 3px;
            color: #333;
        }
        .divider {
            border: none;
            border-top: 2.5px solid #000;
            margin-bottom: 2px;
        }
        .divider-thin {
            border: none;
            border-top: 1px solid #000;
            margin-bottom: 10px;
        }

        /* JUDUL */
        .judul-surat {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 12px 0 4px 0;
            letter-spacing: 0.5px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 9pt;
            margin-bottom: 14px;
        }

        /* INFO TAGIHAN */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 9.5pt;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .info-label { width: 38%; }
        .info-sep   { width: 4%; }
        .info-val   { width: 58%; }

        /* TABEL ITEM */
        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        .tbl th, .tbl td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .tbl th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        .tbl td.center { text-align: center; }
        .tbl td.right  { text-align: right; }

        /* TOTAL */
        .total-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 6px;
        }
        .total-table td {
            padding: 3px 6px;
            vertical-align: middle;
        }
        .total-table .total-label {
            text-align: right;
            width: 70%;
            font-weight: bold;
        }
        .total-table .total-sep {
            text-align: center;
            width: 5%;
        }
        .total-table .total-val {
            text-align: right;
            width: 25%;
            border: 1.5px solid #000;
            font-weight: bold;
            font-size: 10pt;
        }
        .total-table .subtotal-val {
            text-align: right;
            width: 25%;
            border: 1px solid #ccc;
        }

        /* TERBILANG */
        .terbilang-box {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 9pt;
            margin-bottom: 16px;
            font-style: italic;
        }

        /* REKENING */
        .rekening-box {
            border: 1px solid #000;
            padding: 7px 10px;
            font-size: 9pt;
            margin-bottom: 16px;
            background: #f9f9f9;
        }
        .rekening-box .rek-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        .rekening-box table { width: 100%; border-collapse: collapse; }
        .rekening-box table td { padding: 1.5px 0; font-size: 9pt; }
        .rekening-box table td:first-child { width: 38%; }
        .rekening-box table td:nth-child(2) { width: 4%; }

        /* TTD */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 10px;
        }
        .ttd-table td {
            vertical-align: top;
            padding: 0 5px;
            text-align: center;
            width: 50%;
        }
        .ttd-space { height: 55px; }

        /* CATATAN */
        .catatan {
            font-size: 8pt;
            color: #333;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<?php
    $no_surat       = $no_surat ?? 'XXX/LSP-MKC/TAG/'.date('Y');
    $tanggal_surat  = $tanggal_surat ?? date('d F Y');
    $nama_asesi     = $nama_asesi ?? '';
    $nik            = $nik ?? '';
    $no_reg         = $no_reg ?? '';
    $alamat         = $alamat ?? '';
    $nama_skema     = $nama_skema ?? '';
    $kode_skema     = $kode_skema ?? '';
    $items          = $items ?? [];
    $lsp_nama       = $lsp_nama ?? 'LSP MITRA KARYA CEMERLANG';
    $lsp_alamat     = $lsp_alamat ?? 'Jl. Contoh No. 123, Kota, Provinsi';
    $lsp_telp       = $lsp_telp ?? '';
    $lsp_email      = $lsp_email ?? '';
    $rek_bank       = $rek_bank ?? 'BNI';
    $rek_no         = $rek_no ?? '';
    $rek_atas_nama  = $rek_atas_nama ?? $lsp_nama;
    $kota           = $kota ?? 'Jakarta';
    $nama_ttd       = $nama_ttd ?? '';
    $jabatan_ttd    = $jabatan_ttd ?? 'Manajer Sertifikasi';

    $subtotal   = collect($items)->sum(fn($i) => ($i['qty'] ?? 1) * ($i['harga'] ?? 0));
    $diskon     = $diskon ?? 0;
    $ppn_persen = $ppn_persen ?? 0;
    $ppn        = $ppn_persen > 0 ? round($subtotal * $ppn_persen / 100) : 0;
    $total      = $subtotal - $diskon + $ppn;

    // Fungsi terbilang sederhana
    function terbilang($angka) {
        $satuan = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
                   'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
                   'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
        if ($angka < 20) return $satuan[$angka];
        if ($angka < 100) return $satuan[intval($angka/10)].' puluh '.($angka%10 ? $satuan[$angka%10] : '');
        if ($angka < 1000) return $satuan[intval($angka/100)].' ratus '.($angka%100 ? terbilang($angka%100) : '');
        if ($angka < 1000000) return terbilang(intval($angka/1000)).' ribu '.($angka%1000 ? terbilang($angka%1000) : '');
        if ($angka < 1000000000) return terbilang(intval($angka/1000000)).' juta '.($angka%1000000 ? terbilang($angka%1000000) : '');
        return terbilang(intval($angka/1000000000)).' miliar '.($angka%1000000000 ? terbilang($angka%1000000000) : '');
    }
    $terbilang = ucfirst(trim(terbilang($total))).' rupiah';
?>


<table class="header-table">
    <tr>
        <td class="logo-cell">
            <div class="logo-box">LOGO</div>
        </td>
        <td class="lsp-info">
            <div class="lsp-name"><?php echo e(strtoupper($lsp_nama)); ?></div>
            <div class="lsp-sub">Lembaga Sertifikasi Profesi</div>
            <div class="lsp-alamat">
                <?php echo e($lsp_alamat); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lsp_telp): ?> &nbsp;|&nbsp; Telp: <?php echo e($lsp_telp); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lsp_email): ?> &nbsp;|&nbsp; <?php echo e($lsp_email); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </td>
        <td class="logo-cell">
            <div class="logo-box">BNSP</div>
        </td>
    </tr>
</table>
<hr class="divider">
<hr class="divider-thin">


<div class="judul-surat">SURAT TAGIHAN BIAYA SERTIFIKASI</div>
<div class="nomor-surat">Nomor: <?php echo e($no_surat); ?></div>


<table class="info-table">
    <tr>
        <td class="info-label">Kepada Yth.</td>
        <td class="info-sep">:</td>
        <td class="info-val" style="font-weight:bold;"><?php echo e($nama_asesi); ?></td>
    </tr>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nik): ?>
    <tr>
        <td class="info-label">NIK</td>
        <td class="info-sep">:</td>
        <td class="info-val"><?php echo e($nik); ?></td>
    </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($no_reg): ?>
    <tr>
        <td class="info-label">No. Registrasi</td>
        <td class="info-sep">:</td>
        <td class="info-val"><?php echo e($no_reg); ?></td>
    </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alamat): ?>
    <tr>
        <td class="info-label">Alamat</td>
        <td class="info-sep">:</td>
        <td class="info-val"><?php echo e($alamat); ?></td>
    </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <tr>
        <td class="info-label">Skema Sertifikasi</td>
        <td class="info-sep">:</td>
        <td class="info-val"><?php echo e($nama_skema); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kode_skema): ?>(<?php echo e($kode_skema); ?>)<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
    </tr>
    <tr>
        <td class="info-label">Tanggal Tagihan</td>
        <td class="info-sep">:</td>
        <td class="info-val"><?php echo e($tanggal_surat); ?></td>
    </tr>
</table>


<table class="tbl">
    <colgroup>
        <col style="width:7%;">
        <col style="width:52%;">
        <col style="width:8%;">
        <col style="width:16%;">
        <col style="width:17%;">
    </colgroup>
    <thead>
        <tr>
            <th>No.</th>
            <th>Uraian</th>
            <th>Qty</th>
            <th>Harga Satuan (Rp)</th>
            <th>Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <tr>
            <td class="center"><?php echo e($i + 1); ?></td>
            <td>
                <?php echo e($item['uraian']); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['keterangan'])): ?>
                <br><span style="font-size:8pt; color:#444;"><?php echo e($item['keterangan']); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </td>
            <td class="center"><?php echo e($item['qty'] ?? 1); ?></td>
            <td class="right"><?php echo e(number_format($item['harga'] ?? 0, 0, ',', '.')); ?></td>
            <td class="right"><?php echo e(number_format(($item['qty'] ?? 1) * ($item['harga'] ?? 0), 0, ',', '.')); ?></td>
        </tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </tbody>
</table>


<table class="total-table">
    <tr>
        <td class="total-label">Subtotal</td>
        <td class="total-sep">:</td>
        <td class="subtotal-val">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></td>
    </tr>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($diskon > 0): ?>
    <tr>
        <td class="total-label">Diskon</td>
        <td class="total-sep">:</td>
        <td class="subtotal-val">Rp <?php echo e(number_format($diskon, 0, ',', '.')); ?></td>
    </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ppn > 0): ?>
    <tr>
        <td class="total-label">PPN (<?php echo e($ppn_persen); ?>%)</td>
        <td class="total-sep">:</td>
        <td class="subtotal-val">Rp <?php echo e(number_format($ppn, 0, ',', '.')); ?></td>
    </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <tr>
        <td class="total-label" style="font-size:10.5pt;">TOTAL</td>
        <td class="total-sep">:</td>
        <td class="total-val">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></td>
    </tr>
</table>


<div class="terbilang-box">
    Terbilang: <strong><?php echo e($terbilang); ?></strong>
</div>


<div class="rekening-box">
    <div class="rek-title">Pembayaran ditransfer ke:</div>
    <table>
        <tr>
            <td>Bank</td>
            <td style="width:4%;">:</td>
            <td><?php echo e($rek_bank); ?></td>
        </tr>
        <tr>
            <td>No. Rekening</td>
            <td>:</td>
            <td><strong><?php echo e($rek_no); ?></strong></td>
        </tr>
        <tr>
            <td>Atas Nama</td>
            <td>:</td>
            <td><?php echo e($rek_atas_nama); ?></td>
        </tr>
    </table>
</div>


<table class="ttd-table">
    <tr>
        <td></td>
        <td><?php echo e($kota); ?>, <?php echo e($tanggal_surat); ?></td>
    </tr>
    <tr>
        <td>Diterima oleh,</td>
        <td>Hormat kami,</td>
    </tr>
    <tr>
        <td class="ttd-space"></td>
        <td class="ttd-space"></td>
    </tr>
    <tr>
        <td>
            <div style="border-top:1px solid #000; display:inline-block; width:80%; padding-top:2px;">
                (_____________________________)
            </div>
        </td>
        <td>
            <strong><?php echo e($nama_ttd); ?></strong><br>
            <?php echo e($jabatan_ttd); ?>

        </td>
    </tr>
</table>


<div class="catatan">
    * Mohon melampirkan surat tagihan ini sebagai bukti pembayaran.
</div>

</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/invoice.blade.php ENDPATH**/ ?>