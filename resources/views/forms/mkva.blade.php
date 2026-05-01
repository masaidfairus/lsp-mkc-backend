{{-- resources/views/forms/VA/frva.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.VA - Memberikan Kontribusi dalam Validasi Asesmen</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 1.5cm 1.5cm 2cm 1.5cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
        }

        footer {
            position: fixed;
            bottom: -0.8cm;
            left: 0;
            right: 0;
            height: 0.8cm;
            font-size: 7pt;
            color: #555;
        }
        .footer-line {
            height: 4px;
            background-color: #e8742a;
            width: 100%;
            margin-bottom: 2px;
        }
        .pagenum:before {
            content: counter(page);
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
            display: inline-block;
            vertical-align: middle;
            margin-right: 4px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #000;
        }

        /* ── Signature Table ── */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .sig-table td {
            width: 50%;
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }
        .sig-box {
            height: 70px;
            border-bottom: 1px dotted #000;
            margin: 10px 0;
            position: relative;
        }
        .sig-label {
            font-size: 8pt;
            margin-bottom: 5px;
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
            table-layout: fixed;
        }
        .temuan-table th, .temuan-table td {
            border: 1px solid #000;
            padding: 5px 7px;
            font-size: 8.5pt;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: normal;
        }
        .temuan-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }
        .temuan-table .col-no    { width: 30px; text-align: center; }
        .temuan-table .col-temuan{ width: 47%; }
        .temuan-table .col-rekom { width: auto; }
        .temuan-table .data-row td { height: auto; min-height: 55px; vertical-align: top; }

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



        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <footer>
        <div class="footer-line"></div>
        <table style="width: 100%; border:none; margin-top:5px">
            <tr>
                <td style="border:none; text-align:left; font-size:7pt">
                    Badan Nasional Sertifikasi Profesi<br>
                    Jl. MT. Haryono Kavling 52, Jakarta, Indonesia
                </td>
                <td style="border:none; text-align:right; font-size:7pt">
                    Halaman <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </footer>

    {{-- LOGO + JUDUL --}}
    <div class="header-top">
        <img src="{{ public_path('images/logo_bnsp.png') }}" height="40" alt="">
    </div>
    <div class="form-title">FR.VA MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN</div>

    {{-- IDENTITAS --}}
    <table class="ident-table">
        <tr>
            <td style="width:18%; font-weight:bold" rowspan="3">Tim Validasi</td>
            <td style="width:3%; text-align:center" rowspan="3">:</td>
            <td style="width:30%">1. {{ $tim_validasi[0] ?? '' }}</td>
            <td style="width:15%">Hari/Tanggal</td>
            <td style="width:3%; text-align:center">:</td>
            <td>{{ $hari_tanggal }}</td>
        </tr>
        <tr>
            <td>2. {{ $tim_validasi[1] ?? '' }}</td>
            <td rowspan="2">Tempat</td>
            <td rowspan="2" style="text-align:center">:</td>
            <td rowspan="2">{{ $tempat }}</td>
        </tr>
        <tr>
            <td>3. {{ $tim_validasi[2] ?? '' }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td style="text-align:center">:</td>
            <td colspan="4">
                <span class="checkbox">{!! in_array('before_assessment', $periode ?? []) ? '&#9745;' : '&#9744;' !!}</span> SEBELUM ASESMEN &nbsp;
                <span class="checkbox">{!! in_array('during_assessment', $periode ?? []) ? '&#9745;' : '&#9744;' !!}</span> PADA SAAT ASESMEN &nbsp;
                <span class="checkbox">{!! in_array('after_assessment', $periode ?? []) ? '&#9745;' : '&#9744;' !!}</span> SETELAH ASESMEN
            </td>
        </tr>
        <tr>
            <td>Nama Skema</td>
            <td style="text-align:center">:</td>
            <td colspan="4" class="val">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor Skema</td>
            <td style="text-align:center">:</td>
            <td colspan="4" class="val">{{ $nomor_skema }}</td>
        </tr>
    </table>

    {{-- BAGIAN 1 --}}
    <div class="section-header">1.&nbsp;&nbsp; Menyiapkan Proses Validasi</div>

    {{-- Tujuan / Konteks / Pendekatan --}}
    <table class="tfp-table">
        <thead>
            <tr>
                <th class="col-a">Tujuan dan Fokus Validasi</th>
                <th class="col-b">Konteks Validasi</th>
                <th class="col-c">Pendekatan Validasi</th>
            </tr>
        </thead>
        <tbody>
            @php $maxRows = max(count($tujuan_fokus), count($konteks_validasi), count($pendekatan_validasi)); @endphp
            @for ($i = 0; $i < $maxRows; $i++)
            <tr class="item-row">
                <td>
                    @if(isset($tujuan_fokus[$i]))
                    <span class="checkbox">{!! !empty($tujuan_fokus[$i]['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $tujuan_fokus[$i]['label'] }}
                    @endif
                </td>
                <td>
                    @if(isset($konteks_validasi[$i]))
                    <span class="checkbox">{!! !empty($konteks_validasi[$i]['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $konteks_validasi[$i]['label'] }}
                    @endif
                </td>
                <td>
                    @if(isset($pendekatan_validasi[$i]))
                    <span class="checkbox">{!! !empty($pendekatan_validasi[$i]['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $pendekatan_validasi[$i]['label'] }}
                    @endif
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

    {{-- Orang yang Relevan --}}
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
            @foreach ($orang_relevan as $index => $o)
            <tr class="data-row">
                <td><span class="checkbox">{!! !empty($o['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $o['label'] }}</td>
                @if($index === 0)
                <td rowspan="{{ count($orang_relevan) }}" style="vertical-align:top; text-align:left; padding: 5px 10px;">
                    {!! nl2br(e($o['nama'])) !!}
                </td>
                <td rowspan="{{ count($orang_relevan) }}" style="vertical-align:top; padding:10px">{!! nl2br(e($o['hasil'])) !!}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>



    {{-- PAGE 2 --}}
    <div class="page-break"></div>

<div class="header-top">
        <img src="{{ public_path('images/logo_bnsp.png') }}" height="40" alt="">
    </div>
    {{-- Acuan Pembanding + Dokumen Terkait --}}
    <table class="acuan-table">
        <thead>
            <tr>
                <th class="col-a">Acuan Pembanding :</th>
                <th class="col-b">Dokumen Terkait dan Bahan-Bahan</th>
            </tr>
        </thead>
        <tbody>
            @php $maxAcuan = max(count($acuan_pembanding), count($dokumen_terkait)); @endphp
            @for ($i = 0; $i < $maxAcuan; $i++)
            <tr class="item-row">
                <td>
                    @if(isset($acuan_pembanding[$i]))
                    <span class="checkbox">{!! !empty($acuan_pembanding[$i]['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $acuan_pembanding[$i]['label'] }}
                    @endif
                </td>
                <td>
                    @if(isset($dokumen_terkait[$i]))
                    <span class="checkbox">{!! !empty($dokumen_terkait[$i]['is_checked']) ? '&#9745;' : '&#9744;' !!}</span>{{ $dokumen_terkait[$i]['label'] }}
                    @endif
                </td>
            </tr>
            @endfor
        </tbody>
    </table>

    {{-- BAGIAN 2 --}}
    <div class="section-header" style="margin-top:10px">2.&nbsp;&nbsp; Memberikan Kontribusi dalam Proses Validasi</div>

    <table class="komunikasi-table">
        <tr>
            <td class="col-label">Keterampilan komunikasi yang digunakan dalam kegiatan validasi :</td>
            <td class="col-opts">
                @php 
                    $allKomunikasi = ['PRO AKTIF', 'ACTIVE LISTENING', 'Empati'];
                    $selectedKomunikasi = array_column($komunikasi, 'label');
                @endphp
                @foreach ($allKomunikasi as $opt)
                <span class="checkbox">{!! in_array($opt, $selectedKomunikasi) ? '&#9745;' : '&#9744;' !!}</span>{{ $opt }}<br>
                @endforeach
            </td>
        </tr>
    </table>

    {{-- Tabel Aspek Validasi --}}
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
            @foreach ($aspek_validasi as $a)
            <tr class="data-row">
                <td class="col-no">{{ $a['no'] }}.</td>
                <td class="col-aspek" style="text-align:left">{{ $a['label'] }}</td>
                <td><span class="checkbox">{!! !empty($a['v']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['a']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['t']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['m']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['pv']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['pr']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['pf']) ? '&#9745;' : '&#9744;' !!}</span></td>
                <td><span class="checkbox">{!! !empty($a['pa']) ? '&#9745;' : '&#9744;' !!}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="note-small">☺ yang perlu</div>

    {{-- BAGIAN 3 --}}
    <div class="section-header" style="margin-top:4px">3.&nbsp;&nbsp; Memberikan Kontribusi untuk Hasil Asesmen</div>

    <table class="temuan-table">
        <thead>
            <tr>
                <th class="col-no"></th>
                <th colspan="8" class="col-temuan">Temuan Validasi :</th>
                <th colspan="8" class="col-rekom">Rekomendasi untuk meningkatkan Praktek Asesmen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($temuan_rekomendasi as $t)
            <tr class="data-row">
                <td class="col-no" style="vertical-align:top; padding-top:6px">{{ $t['no'] }}.</td>
                <td colspan="8">{{ $t['temuan'] }}</td>
                <td colspan="8">{{ $t['rekomendasi'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



    {{-- PAGE 3 --}}
    <div class="page-break"></div>

<div class="header-top">
        <img src="{{ public_path('images/logo_bnsp.png') }}" height="40" alt="">
    </div>
    {{-- Rencana Implementasi --}}
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
            @foreach ($rencana_implementasi as $r)
            <tr class="data-row">
                <td class="col-no" style="vertical-align:top; padding-top:6px">{{ $r['no'] }}.</td>
                <td>{{ $r['kegiatan'] }}</td>
                <td>{{ $r['waktu'] }}</td>
                <td>{{ $r['penanggung_jawab'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



    {{-- Signature Section --}}
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-label">Asesor Kompetensi:</div>
                <div style="font-weight:bold; margin-bottom:10px;">{{ $assessor->name ?? '' }}</div>
                <div class="sig-box" style="display: flex; align-items: center; justify-content: center; height: 100px;">
                    @if($assessor_qr_code)
                        <img src="{{ $assessor_qr_code }}" style="width:70px; height:70px;">
                        <div style="margin-left: 10px; font-size: 8pt; text-align: left;">
                            Telah divalidasi pada:<br>
                            {{ $assessor_date }}
                        </div>
                    @endif
                </div>
                <div class="sig-label">Tanda Tangan / Tanggal</div>
            </td>
            <td>
                <div class="sig-label">Validator:</div>
                <div style="font-weight:bold; margin-bottom:10px;">{{ $validator->name ?? '' }}</div>
                <div class="sig-box" style="display: flex; align-items: center; justify-content: center; height: 100px;">
                    @if($validator_qr_code)
                        <img src="{{ $validator_qr_code }}" style="width:70px; height:70px;">
                        <div style="margin-left: 10px; font-size: 8pt; text-align: left;">
                            Telah divalidasi pada:<br>
                            {{ $validator_date }}
                        </div>
                    @endif
                </div>
                <div class="sig-label">Tanda Tangan / Tanggal</div>
            </td>
        </tr>
    </table>

</body>
</html>