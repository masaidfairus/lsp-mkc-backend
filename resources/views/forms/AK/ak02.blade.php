{{-- resources/views/forms/AK/ak02.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.AK.02 - Rekaman Asesmen Kompetensi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem
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

        /* ── Header ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
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
            margin-bottom: 10px;
            text-align: justify;
        }

        /* ── Tabel Penilaian ── */
        .penilaian-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .penilaian-table th,
        .penilaian-table td {
            border: 1px solid #000;
            font-size: 9pt;
            vertical-align: middle;
            text-align: center;
        }
        .penilaian-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            padding: 5px 4px;
        }

        /* Lebar kolom */
        .col-no     { width: 6%; }
        .col-unit   { width: 30%; text-align: left; padding: 4px 6px; }
        .col-metode { width: 9%; }
        .col-lainnya{ width: 8%; }

        /* Baris unit */
        .unit-row td {
            padding: 4px 6px;
            height: 22px;
        }
        .unit-row .td-center {
            text-align: center;
            padding: 4px 2px;
        }

        /* Baris rekomendasi & tindak lanjut */
        .rekomendasi-row td {
            padding: 5px 7px;
            vertical-align: middle;
        }
        .tindak-row td {
            padding: 5px 7px;
            vertical-align: top;
            height: 50px;
        }
        .komentar-row td {
            padding: 5px 7px;
            vertical-align: top;
            height: 40px;
        }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            margin-right: 3px;
        }

        /* ── TTD ── */
        .ttd-table {
            width: 55%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 14px;
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
        .ttd-table .ttd-row td {
            height: 42px;
        }

        /* ── Lampiran ── */
        .lampiran {
            font-size: 9pt;
            margin-top: 10px;
        }
        .lampiran .lamp-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .lampiran ol {
            padding-left: 18px;
        }
        .lampiran ol li {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- JUDUL --}}
    <div class="form-title">FR.AK.02. REKAMAN ASESMEN KOMPETENSI</div>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td style="width:26%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td style="width:10%">Judul</td>
            <td style="width:3%; text-align:center">:</td>
            <td class="val">{{ $judul }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center">:</td>
            <td class="val">{{ $nomor }}</td>
        </tr>
        <tr>
            <td colspan="2">TUK</td>
            <td style="text-align:center">:</td>
            <td>{{ $tuk }}</td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesor</td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesor }}</td>
        </tr>
        <tr>
            <td colspan="2">Nama Asesi</td>
            <td style="text-align:center">:</td>
            <td>{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td rowspan="2">Tanggal Asesmen</td>
            <td>Mulai</td>
            <td style="text-align:center">:</td>
            <td>{{ $tanggal_mulai }}</td>
        </tr>
        <tr>
            <td>Selesai</td>
            <td style="text-align:center">:</td>
            <td>{{ $tanggal_selesai }}</td>
        </tr>
    </table>

    {{-- TABEL PENILAIAN --}}
    <table class="penilaian-table">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">No.</th>
                <th class="col-unit" rowspan="2" style="text-align:center">Unit Kompetensi</th>
                <th class="col-metode" rowspan="2">Observasi/<br>Demonstrasi</th>
                <th class="col-metode" rowspan="2">Portofolio</th>
                <th class="col-metode" rowspan="2">Pernyataan<br>Pihak Ketiga/<br>Wawancara</th>
                <th class="col-metode" rowspan="2">Pertanyaan<br>Lisan</th>
                <th class="col-metode" rowspan="2">Pertanyaan<br>Tertulis</th>
                <th class="col-metode" rowspan="2">Proyek<br>Kerja</th>
                <th class="col-lainnya">Lainnya</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            {{-- Unit Kompetensi --}}
            @foreach ($unit_kompetensi as $u)
            <tr class="unit-row">
                <td style="text-align:center">{{ $u['no'] }}.</td>
                <td class="col-unit">{{ $u['judul'] }}</td>
                <td class="td-center"></td>
                <td class="td-center"></td>
                <td class="td-center"></td>
                <td class="td-center"></td>
                <td class="td-center"></td>
                <td class="td-center"></td>
                <td class="td-center"></td>
            </tr>
            @endforeach

            {{-- Rekomendasi --}}
            <tr class="rekomendasi-row">
                <td colspan="2" style="font-weight:bold; text-align:left; padding: 5px 7px;">
                    Rekomendasi hasil asesmen
                </td>
                <td colspan="7" style="text-align:left; padding: 5px 10px;">
                    <span class="checkbox"></span> Kompeten
                    &nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
                    <span class="checkbox"></span> Belum kompeten
                </td>
            </tr>

            {{-- Tindak Lanjut --}}
            <tr class="tindak-row">
                <td colspan="2" style="text-align:left; vertical-align:top; padding: 5px 7px;">
                    <strong>Tindak lanjut yang dibutuhkan</strong><br>
                    <span style="font-size:8.5pt">(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</span>
                </td>
                <td colspan="7" style="text-align:left; padding: 5px 7px; vertical-align:top">
                    {{ $tindak_lanjut }}
                </td>
            </tr>

            {{-- Komentar / Observasi --}}
            <tr class="komentar-row">
                <td colspan="2" style="text-align:left; padding: 5px 7px;">
                    Komentar/ Observasi oleh asesor
                </td>
                <td colspan="7" style="text-align:left; padding: 5px 7px; vertical-align:top">
                    {{ $komentar_observasi }}
                </td>
            </tr>

        </tbody>
    </table>

    {{-- TTD --}}
    <table class="ttd-table">
        <tr class="section-header">
            <td colspan="3"><strong>Asesi :</strong></td>
        </tr>
        <tr>
            <td style="width:38%">Nama</td>
            <td style="width:4%; text-align:center">:</td>
            <td>{{ $asesi_nama }}</td>
        </tr>
        <tr class="ttd-row">
            <td>Tanda tangan<br>dan Tanggal</td>
            <td style="text-align:center">:</td>
            <td></td>
        </tr>
        <tr class="section-header">
            <td colspan="3"><strong>Asesor :</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="text-align:center">:</td>
            <td>{{ $asesor_nama }}</td>
        </tr>
        <tr>
            <td>No. Reg</td>
            <td style="text-align:center">:</td>
            <td>{{ $asesor_no_reg }}</td>
        </tr>
        <tr class="ttd-row">
            <td>Tanda tangan<br>dan Tanggal</td>
            <td style="text-align:center">:</td>
            <td></td>
        </tr>
    </table>

    {{-- LAMPIRAN --}}
    <div class="lampiran">
        <div class="lamp-title">LAMPIRAN DOKUMEN:</div>
        <ol>
            <li>Dokumen APL 01 peserta</li>
            <li>Dokumen APL 02 peserta</li>
            <li>Bukti-bukti berkualitas peserta</li>
            <li>Tinjauan proses asesmen</li>
        </ol>
    </div>

</div>
</body>
</html>