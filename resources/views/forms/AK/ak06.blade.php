{{-- resources/views/forms/AK/ak06.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.AK.06 - Meninjau Proses Asesmen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
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
            margin-bottom: 16px;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9pt;
            vertical-align: middle;
        }
        .header-table .val { font-weight: bold; }

        /* Penjelasan box */
        .penjelasan-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 14px;
            font-size: 9pt;
        }
        .penjelasan-box .pen-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .penjelasan-box ul {
            list-style: disc;
            padding-left: 16px;
        }
        .penjelasan-box ul li {
            margin-bottom: 3px;
            text-align: justify;
        }

        .checkbox {
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
        }
        .cell-disabled {
            background-color: #555;
        }

        /* Tabel 1: Kesesuaian prinsip */
        .prinsip-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .prinsip-table th,
        .prinsip-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            vertical-align: middle;
        }
        .prinsip-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: center;
        }
        .prinsip-table .col-aspek  { width: 44%; text-align: left; }
        .prinsip-table .col-check  { width: 14%; text-align: center; }
        .prinsip-table .row-indent td:first-child {
            padding-left: 20px;
        }
        .prinsip-table .row-rekomendasi td {
            padding: 5px 7px;
            height: 35px;
            vertical-align: top;
        }

        /* Tabel 2: Dimensi kompetensi */
        .dimensi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .dimensi-table th,
        .dimensi-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 9pt;
            vertical-align: middle;
            text-align: center;
        }
        .dimensi-table th {
            background-color: #e8e8e8;
            font-weight: bold;
        }
        .dimensi-table .col-aspek   { width: 22%; text-align: left; }
        .dimensi-table .col-dimensi { width: 15%; }
        .dimensi-table .row-data td { height: 60px; vertical-align: top; }
        .dimensi-table .row-rekomendasi td {
            text-align: left;
            height: 35px;
            vertical-align: top;
        }

        /* Tabel 3: Lead Asesor */
        .lead-table {
            width: 100%;
            border-collapse: collapse;
        }
        .lead-table th,
        .lead-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9pt;
            vertical-align: top;
        }
        .lead-table th {
            background-color: #e8e8e8;
            font-weight: bold;
            text-align: left;
        }
        .lead-table .col-nama   { width: 28%; }
        .lead-table .col-ttd    { width: 36%; }
        .lead-table .col-komen  { width: 36%; }
        .lead-table .data-row td { height: 55px; }
    </style>
</head>
<body>

    {{-- JUDUL --}}
    <div class="form-title">FR.AK.06.&nbsp;&nbsp;&nbsp; MENINJAU PROSES ASESMEN</div>

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
            <td colspan="2">Tanggal</td>
            <td style="text-align:center">:</td>
            <td>{{ $tanggal }}</td>
        </tr>
    </table>

    {{-- PENJELASAN --}}
    <div class="penjelasan-box">
        <div class="pen-title">Penjelasan:</div>
        <ul>
            <li>Peninjauan dapat dilakukan oleh lead asesor atau asesor yang melaksanakan asesmen.</li>
            <li>Peninjauan dapat dilakukan secara terpadu dalam skema sertifikasi dan / atau peserta kelompok yang homogen.</li>
            <li>Isilah pemenuhan dimensi kompetensi dengan menulis kode rekaman formulir yang membuktikan terpenuhinya dimensi kompetensi.</li>
        </ul>
    </div>

    {{-- TABEL 1: KESESUAIAN PRINSIP ASESMEN --}}
    <table class="prinsip-table">
        <thead>
            <tr>
                <th class="col-aspek" rowspan="2">Asepek yang ditinjau</th>
                <th colspan="4">Kesesuaian dengan prinsip asesmen</th>
            </tr>
            <tr>
                <th class="col-check">Validitas</th>
                <th class="col-check">Reliabel</th>
                <th class="col-check">Fleksibel</th>
                <th class="col-check">Adil</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            {{-- Baris header prosedur --}}
            <tr>
                <td style="font-weight:normal">Prosedur asesmen:</td>
                <td style="text-align:center"><span class="checkbox"></span></td>
                <td style="text-align:center"><span class="checkbox"></span></td>
                <td style="text-align:center"><span class="checkbox"></span></td>
                <td style="text-align:center"><span class="checkbox"></span></td>
            </tr>
            {{-- Sub-baris prosedur --}}
            @foreach ($prosedur_asesmen as $p)
            <tr class="row-indent">
                <td style="padding-left:20px">
                    <span style="margin-right:5px">&#8226;</span>{{ $p['label'] }}
                </td>
                <td style="text-align:center">
                    @if(!in_array('validitas', $p['disabled']))
                        <span class="checkbox"></span>
                    @else
                        <span class="cell-disabled">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if(!in_array('reliabel', $p['disabled']))
                        <span class="checkbox"></span>
                    @else
                        <span class="cell-disabled">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if(!in_array('fleksibel', $p['disabled']))
                        <span class="checkbox"></span>
                    @else
                        <span class="cell-disabled">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if(!in_array('adil', $p['disabled']))
                        <span class="checkbox"></span>
                    @else
                        <span class="cell-disabled">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
            </tr>
            @endforeach
            {{-- Rekomendasi --}}
            <tr class="row-rekomendasi">
                <td colspan="5" style="text-align:left; padding: 5px 7px; vertical-align:top">
                    Rekomendasi untuk peningkatan<br>{{ $rekomendasi_prinsip }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL 2: PEMENUHAN DIMENSI KOMPETENSI --}}
    <table class="dimensi-table">
        <thead>
            <tr>
                <th class="col-aspek" rowspan="2">Aspek yang ditinjau</th>
                <th colspan="5">Pemenuhan dimensi kompetensi</th>
            </tr>
            <tr>
                <th class="col-dimensi">Task Skills</th>
                <th class="col-dimensi">Task<br>Management<br>Skills</th>
                <th class="col-dimensi">Contingency<br>Management<br>Skills</th>
                <th class="col-dimensi">Job Role/<br>Environment<br>Skills</th>
                <th class="col-dimensi">Transfer Skills</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            <tr class="row-data">
                <td class="col-aspek" style="text-align:left; vertical-align:top">
                    <strong>Konsistensi keputusan asesmen</strong><br>
                    Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi
                </td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
                <td><span class="checkbox"></span></td>
            </tr>
            <tr class="row-rekomendasi">
                <td colspan="6" style="text-align:left; padding: 5px 7px; vertical-align:top">
                    Rekomendasi untuk peningkatan:<br>{{ $rekomendasi_dimensi }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL LEAD ASESOR --}}
    <table class="lead-table">
        <thead>
            <tr>
                <th class="col-nama">Nama Lead<br>Asesor/Asesor</th>
                <th class="col-ttd">Tanggal &amp; Tanda Tangan</th>
                <th class="col-komen">Komentar</th>
            </tr>
        </thead>
        <tbody>
            <tr class="data-row">
                <td>{{ $lead_asesor_nama }}</td>
                <td>{{ $lead_asesor_ttd }}</td>
                <td>{{ $lead_asesor_komentar }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>