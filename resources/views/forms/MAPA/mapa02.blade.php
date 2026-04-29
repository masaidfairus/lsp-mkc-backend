<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.MAPA.02</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            padding: 4rem;
        }
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
            size: A4 portrait;
        }
        .page-break { page-break-before: always; }
        .judul-utama {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .judul-sub {
            font-size: 9pt;
            text-align: center;
            margin-bottom: 15px;
        }
        .tbl-skema {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .tbl-skema td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
        }
        .tbl-unit {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .tbl-unit th { border: 1px solid #000; padding: 5px; background: #ddd; font-weight: bold; }
        .tbl-unit td { border: 1px solid #000; padding: 5px 7px; }

        .tbl-instrumen {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
        }
        .tbl-instrumen th, .tbl-instrumen td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 8.5pt;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .tbl-instrumen th { background: #ececec; font-weight: bold; }
        .tbl-instrumen td.left { text-align: left; }

        .cb {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
        }
        .cb-on {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            background: #000;
            vertical-align: middle;
        }

        .keterangan {
            font-size: 8.5pt;
            margin-bottom: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .keterangan ol { padding-left: 20px; }
        .keterangan li { margin-bottom: 3px; }

        .tbl-footer {
            width: 100%;
            border-collapse: collapse;
        }
        .tbl-footer td { border: 1px solid #000; padding: 6px 10px; vertical-align: middle; }
        .tbl-footer .lbl { width: 35%; font-weight: bold; background: #fafafa; }
        .sig-image { max-height: 50px; display: inline-block; vertical-align: middle; }
    </style>
</head>
<body>

    @php
        $nama_skema = $nama_skema ?? '';
        $kode_skema = $kode_skema ?? '';
        $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
        $penyusun_nama = $penyusun_nama ?? '';
        $penyusun_no_reg = $penyusun_no_reg ?? '';
        $penyusun_date = $penyusun_date ?? '';
        $penyusun_qr_code = $penyusun_qr_code ?? null;
        $validator_nama = $validator_nama ?? '';
        $validator_no_reg = $validator_no_reg ?? '';
        $validator_date = $validator_date ?? '';
        $validator_qr_code = $validator_qr_code ?? null;
    @endphp

    <div class="judul-utama">FR.MAPA.02 – PETA INSTRUMEN ASESMEN</div>
    <div class="judul-sub">Hasil Pendekatan Asesmen dan Perencanaan Asesmen</div>

    <table class="tbl-skema">
        <colgroup>
            <col style="width:25%;">
            <col style="width:10%;">
            <col style="width:3%;">
            <col style="width:62%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="font-weight:bold;">Skema Sertifikasi</td>
            <td>Judul</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $kode_skema }}</td>
        </tr>
    </table>

    @foreach($kelompok_pekerjaan as $kp)
        @if(!$loop->first) <div class="page-break"></div> @endif
        
        <table class="tbl-unit">
            <thead>
                <tr>
                    <th style="width:20%;">Kelompok Kerja {{ $kp['no'] }}</th>
                    <th style="width:5%;">No</th>
                    <th style="width:25%;">Kode Unit</th>
                    <th style="text-align:left; padding-left:10px;">Judul Unit Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kp['unit'] as $idx => $unit)
                <tr>
                    @if($idx === 0) <td rowspan="{{ count($kp['unit']) }}" style="text-align:center; font-weight:bold;">{{ $kp['nama'] }}</td> @endif
                    <td style="text-align:center;">{{ $idx + 1 }}</td>
                    <td>{{ $unit['kode'] }}</td>
                    <td>{{ $unit['judul'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="tbl-instrumen">
            <thead>
                <tr>
                    <th rowspan="2" style="width:8%;">No</th>
                    <th rowspan="2" style="width:62%;">Instrumen Asesmen</th>
                    <th colspan="5">Potensi Asesi</th>
                </tr>
                <tr>
                    <th style="width:6%;">1</th>
                    <th style="width:6%;">2</th>
                    <th style="width:6%;">3</th>
                    <th style="width:6%;">4</th>
                    <th style="width:6%;">5</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kp['assessment_instruments'] ?? [] as $i => $inst)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="left">{{ $inst['method'] ?? '' }}</td>
                    <td><span class="{{ ($inst['answer'] ?? '') == '1' ? 'cb-on' : 'cb' }}"></span></td>
                    <td><span class="{{ ($inst['answer'] ?? '') == '2' ? 'cb-on' : 'cb' }}"></span></td>
                    <td><span class="{{ ($inst['answer'] ?? '') == '3' ? 'cb-on' : 'cb' }}"></span></td>
                    <td><span class="{{ ($inst['answer'] ?? '') == '4' ? 'cb-on' : 'cb' }}"></span></td>
                    <td><span class="{{ ($inst['answer'] ?? '') == '5' ? 'cb-on' : 'cb' }}"></span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="keterangan">
        <strong>Keterangan Potensi Asesi:</strong>
        <ol>
            <li>Hasil pelatihan dan/atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi.</li>
            <li>Hasil pelatihan dan/atau pendidikan, dimana kurikulum belum berbasis kompetensi.</li>
            <li>Pekerja berpengalaman dari industri/tempat kerja yang operasionalnya mampu telusur dengan standar kompetensi.</li>
            <li>Pekerja berpengalaman dari industri/tempat kerja yang operasionalnya belum berbasis kompetensi.</li>
            <li>Pelatihan / belajar mandiri atau otodidak.</li>
        </ol>
    </div>

    <div style="font-weight:bold; margin-bottom:5px;">Penyusun dan Validator</div>
    <table class="tbl-footer">
        <tr>
            <td class="lbl">Nama Penyusun</td>
            <td>{{ $penyusun_nama }}</td>
        </tr>
        <tr>
            <td class="lbl">No. Reg</td>
            <td>{{ $penyusun_no_reg }}</td>
        </tr>
        <tr>
            <td class="lbl">Tanda Tangan & Tanggal</td>
            <td style="height:70px;">
                @if($penyusun_qr_code)
                    <img src="{{ $penyusun_qr_code }}" class="sig-image">
                @endif
                <span style="margin-left:10px;">{{ $penyusun_date }}</span>
            </td>
        </tr>
        <tr>
            <td class="lbl">Nama Validator</td>
            <td>{{ $validator_nama }}</td>
        </tr>
        <tr>
            <td class="lbl">No. Reg</td>
            <td>{{ $validator_no_reg }}</td>
        </tr>
        <tr>
            <td class="lbl">Tanda Tangan & Tanggal</td>
            <td style="height:70px;">
                @if($validator_qr_code)
                    <img src="{{ $validator_qr_code }}" class="sig-image">
                @endif
                <span style="margin-left:10px;">{{ $validator_date }}</span>
            </td>
        </tr>
    </table>

</body>
</html>