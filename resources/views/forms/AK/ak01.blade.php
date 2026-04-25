<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.01</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .judul {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

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

        .cb,
        .cb-checked {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            position: relative;
        }

        .cb-checked::after {
            display: inline-block;
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

        .cb-set {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
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

    @php
        $scheme_name = $scheme_name ?? '';
        $scheme_code = $scheme_code ?? '';
        $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
        $asesor_name = $asesor_name ?? '';
        $asesi_name = $asesi_name ?? '';
        $assessment_date = $assessment_date ?? '';
        $agreement_time = $agreement_time ?? '';
        $agreement_tuk = $agreement_tuk ?? '';
        $evidence_map = $evidence_map ?? '';
        $asesor_sign_date = $asesor_sign_date ?? '';
        $asesi_sign_date = $asesi_sign_date ?? '';
    @endphp

    <div class="judul">FR.AK.01. &nbsp; PERSETUJUAN ASESMEN DAN KERAHASIAAN</div>

    <table class="tbl">
        <colgroup>
            <col style="width:100%;">
        </colgroup>

        {{-- Deskripsi --}}
        <tr>
            <td style="font-size:8.5pt; line-height:1.5;">
                Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan
                dan proses asesmen
            </td>
        </tr>
    </table>

    {{-- Info skema, asesor, asesi --}}
    <table class="tbl" style="border-top:none;">
        <colgroup>
            <col style="width:32%;">
            <col style="width:16%;">
            <col style="width:4%;">
            <col style="width:48%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)*</td>
            <td>Judul :</td>
            <td style="font-weight:bold;" colspan="4">{{ $scheme_name }}</td>
        </tr>
        <tr>
            <td>Nomor :</td>
            <td style="font-weight:bold;" colspan="4">{{ $scheme_code }}</td>
        </tr>
        <tr>
            <td colspan="3">TUK :</td>
            <td colspan="4">{{ $tuk }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesor :</td>
            <td colspan="4">{{ $asesor_name }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesi :</td>
            <td colspan="4">{{ $asesi_name }}</td>
        </tr>

        {{-- Bukti --}}
        <tr>
            <td colspan="3" style="vertical-align:middle;">Bukti yang akan dikumpulkan :</td>
            <td style="padding: 8px; line-height: 2; border-right: 1px solid white" colspan="2">
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Hasil Verifikasi Portofolio'] ?? false) ? 'cb-checked' : 'cb' }}"></span>
                    Hasil Verifikasi Portofolio</span>
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Hasil Reviu Produk'] ?? false) ? 'cb-checked' : 'cb' }}"></span> Hasil
                    Reviu Produk</span>
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Hasil Observasi Langsung'] ?? false) ? 'cb-checked' : 'cb' }}"></span>
                    Hasil Observasi Langsung</span>
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Hasil Kegiatan Terstruktur'] ?? false) ? 'cb-checked' : 'cb' }}"></span>
                    Hasil Kegiatan Terstruktur</span>
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Lainnya'] ?? false) ? 'cb-checked' : 'cb' }}"></span> Lainnya
                    ......</span>
            </td>
            <td style="padding: 8px; line-height: 2; border-left: 1px solid white" colspan="2">
                <span class="cb-set"><span
                        class="{{ ($evidence_map['Hasil Tanya Jawab'] ?? false) ? 'cb-checked' : 'cb' }}"></span> Hasil
                    Tanya Jawab</span>
                <span class="cb-set"><span class="cb"></span> Daftar Pertanyaan Tertulis</span>
                <span class="cb-set"><span class="cb"></span> Daftar Pertanyaan Lisan</span>
                <span class="cb-set"><span class="cb"></span> Daftar Pertanyaan Wawancara</span>
            </td>
        </tr>

        {{-- Pelaksanaan --}}
        <tr>
            <td colspan="3" style="vertical-align:middle;">Pelaksanaan
                asesmen disepakati pada:</td>
            <td style="padding:0;" colspan="4">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td
                            style="width:35%; padding:5px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">
                            Hari / Tanggal</td>
                        <td
                            style="width:4%; text-align:center; padding:5px 3px; border-right:1px solid #000; border-bottom:1px solid #000;">
                            :</td>
                        <td style="padding:5px 7px; border-bottom:1px solid #000;">{{ $assessment_date }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 7px; border-right:1px solid #000; border-bottom:1px solid #000;">Waktu
                        </td>
                        <td
                            style="text-align:center; padding:5px 3px; border-right:1px solid #000; border-bottom:1px solid #000;">
                            :</td>
                        <td style="padding:5px 7px; border-bottom:1px solid #000;">{{ $agreement_time }}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 7px; border-right:1px solid #000;">TUK</td>
                        <td style="text-align:center; padding:5px 3px; border-right:1px solid #000;">:</td>
                        <td style="padding:5px 7px;">{{ $agreement_tuk }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Asesor --}}
    <div class="section-label">Asesor :</div>
    <table class="tbl">
        <tr>
            <td style="line-height:1.6; font-size:8.5pt;">
                Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor
                dalam pekerjaan <em>Asesmen</em> kepada siapapun atau organisasi apapun selain kepada pihak yang
                berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
            </td>
        </tr>
    </table>

    {{-- Asesi --}}
    <div class="section-label" style="border-top:none;">Asesi :</div>
    <table class="tbl" style="border-top:none;">
        <tr>
            <td style="line-height:1.6; font-size:8.5pt;">
                Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk
                pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
            </td>
        </tr>
    </table>

    {{-- TTD --}}
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
                Tanda tangan Asesor : {{ $asesor_name }}
            </td>
            <td style="vertical-align:bottom; padding-bottom:5px; border-left:none; border-right:none;" colspan="2">
                Tanggal : {{ $asesor_sign_date }}
            </td>
            <td style="border-left:none;"></td>
        </tr>
        <tr style="padding: 1rem">
            <td style="height:50px; vertical-align:bottom; padding-bottom:5px; border-right:none;" colspan="2">
                Tanda tangan Asesi : {{ $asesi_name }}
            </td>
            <td style="vertical-align:bottom;a padding-bottom:5px; border-left:none; border-right:none;" colspan="2">
                Tanggal : {{ $asesi_sign_date }}
            </td>
            <td style="border-left:none;"></td>
        </tr>
    </table>

</body>

</html>