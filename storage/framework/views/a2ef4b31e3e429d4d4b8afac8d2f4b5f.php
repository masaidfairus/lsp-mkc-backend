<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.AK.01</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem
        }
        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }

        .judul { font-size: 10pt; font-weight: bold; margin-bottom: 8px; }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 9pt;
        }

        .cb {
            display: inline-block;
            width: 10px; height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 4px;
        }

        .cb-set {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 6px;
            white-space: nowrap;
        }

        .section-label {
            font-weight: bold;
            background: #f0f0f0;
            padding: 4px 7px;
            border: 1px solid #000;
            border-bottom: none;
            font-size: 9pt;
        }

        .note {
            font-size: 7.5pt;
            font-style: italic;
            margin-top: 6px;
            color: #333;
        }
    </style>
</head>
<body>

<?php
    $nama_skema  = $nama_skema ?? '';
    $kode_skema  = $kode_skema ?? '';
    $tuk         = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi  = $nama_asesi ?? '';
    $hari_tanggal= $hari_tanggal ?? '';
    $waktu       = $waktu ?? '';
    $tuk_pelaksanaan = $tuk_pelaksanaan ?? '';
?>

<div class="judul">FR.AK.01. &nbsp; PERSETUJUAN ASESMEN DAN KERAHASIAAN</div>

<table class="tbl">
    <colgroup>
        <col style="width:100%;">
    </colgroup>

    
    <tr>
        <td style="font-size:8.5pt; line-height:1.5;">
            Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
        </td>
    </tr>
</table>


<table class="tbl" style="border-top:none;">
    <colgroup>
        <col style="width:32%;">
        <col style="width:16%;">
        <col style="width:4%;">
        <col style="width:48%;">
    </colgroup>
    <tr>
        <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)*</td>
        <td>Judul</td>
        <td style="text-align:center;" colspan="1">:</td>
        <td style="font-weight:bold;" colspan="3"><?php echo e($nama_skema); ?></td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td style="text-align:center;">:</td>
        <td style="font-weight:bold;" colspan="3"><?php echo e($kode_skema); ?></td>
    </tr>
    <tr>
        <td colspan="3">TUK</td>
        <td style="text-align:center;">:</td>
        <td colspan="3"><?php echo e($tuk); ?></td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesor</td>
        <td style="text-align:center;">:</td>
        <td colspan="3"><?php echo e($nama_asesor); ?></td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesi</td>
        <td style="text-align:center;">:</td>
        <td colspan="3"><?php echo e($nama_asesi); ?></td>
    </tr>

    
    <tr>
        <td colspan="3" style="vertical-align:middle;">Bukti yang akan dikumpulkan :</td>
        <td style="text-align:center;">:</td>
        <td style="padding: 8px 7px; line-height: 2;" colspan="3">
            <span class="cb-set"><span class="cb"></span>Hasil Verifikasi Portofolio</span>
            <span class="cb-set"><span class="cb"></span>Hasil Reviu Produk</span><br>
            <span class="cb-set"><span class="cb"></span>Hasil Observasi Langsung</span>
            <span class="cb-set"><span class="cb"></span>Hasil Kegiatan Terstruktur</span><br>
            <span class="cb-set"><span class="cb"></span>Hasil Tanya Jawab</span>
            <span class="cb-set"><span class="cb"></span>Lainnya ......</span>
        </td>
    </tr>

    
    <tr>
        <td colspan="3" rowspan="3" style="vertical-align:middle; border-bottom: black 1px solid;">Pelaksanaan asesmen disepakati pada:</td>
        <td style="text-align:center; border-bottom:black 1px solid;"></td>
        <td style="padding:0; border-bottom: black 1px solid;" colspan="3">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:35%; padding:5px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">Hari / Tanggal</td>
                    <td style="width:4%; text-align:center; padding:5px 3px; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="padding:5px 7px; border-bottom:1px solid #000;"><?php echo e($hari_tanggal); ?></td>
                </tr>
                <tr>
                    <td style="padding:5px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">Waktu</td>
                    <td style="text-align:center; padding:5px 3px; border-right:1px solid #000; border-bottom:1px solid #000;">:</td>
                    <td style="padding:5px 7px; border-bottom:1px solid #000;"><?php echo e($waktu); ?></td>
                </tr>
                <tr>
                    <td style="padding:5px 7px; border-right:1px solid #000;">TUK</td>
                    <td style="text-align:center; padding:5px 3px; border-right:1px solid #000;">:</td>
                    <td style="padding:5px 7px;"><?php echo e($tuk_pelaksanaan); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<div class="section-label">Asesor :</div>
<table class="tbl">
    <tr>
        <td style="line-height:1.6; font-size:8.5pt;">
            Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan <em>Asesmen</em> kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
        </td>
    </tr>
</table>


<div class="section-label" style="border-top:none;">Asesi :</div>
<table class="tbl" style="border-top:none;">
    <tr>
        <td style="line-height:1.6; font-size:8.5pt;">
            Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
        </td>
    </tr>
</table>


<table class="tbl" style="border-top:none;">
    <colgroup>
        <col style="width:40%;">
        <col style="width:20%;">
        <col style="width:40%;">
        <col style="width:40%;">
        <col style="width:20%;">
        <col style="width:40%;">
    </colgroup>
    <tr style="padding: 1rem">
        <td style="height:50px; vertical-align:bottom; padding-bottom:5px; border-right:none;" colspan="2">
            Tanda tangan Asesor : ..............................
        </td>
        <td style="vertical-align:bottom; padding-bottom:5px; border-left:none; border-right:none;" colspan="2">
            Tanggal : ..............................
        </td>
        <td style="border-left:none;"></td>
    </tr>
    <tr style="padding: 1rem">
        <td style="height:50px; vertical-align:bottom; padding-bottom:5px; border-right:none;" colspan="2">
            Tanda tangan Asesi : ..............................
        </td>
        <td style="vertical-align:bottom; padding-bottom:5px; border-left:none; border-right:none;" colspan="2">
            Tanggal : ..............................
        </td>
        <td style="border-left:none;"></td>
    </tr>
</table>

</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/forms/AK/ak01.blade.php ENDPATH**/ ?>