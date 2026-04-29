<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.MAPA.01</title>
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
            margin-bottom: 10px;
        }
        .cb {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            vertical-align: middle;
            margin-right: 4px;
        }
        .cb-on {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            background: #000;
            vertical-align: middle;
            margin-right: 4px;
        }
        .cb-row { margin-bottom: 5px; line-height: 1.4; }
        .tbl-skema {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .tbl-skema td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: middle;
        }
        .tbl-b1 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            table-layout: fixed;
        }
        .tbl-b1 td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .tbl-b1 .no-cell { width: 6%; text-align: center; font-weight: bold; background: #f5f5f5; }
        .tbl-b1 .label-cell { width: 20%; font-weight: bold; background: #fafafa; }
        .tbl-b1 .section-header { background: #e8e8e8; font-weight: bold; font-size: 9.5pt; }
        
        .inner-tbl { width: 100%; border-collapse: collapse; }
        .inner-tbl td { border: 1px solid #bbb; padding: 4px 6px; }
        .inner-tbl td:first-child { width: 35%; font-weight: bold; background: #f9f9f9; }

        .kp-label {
            background: #f0f0f0;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #000;
        }
        .tbl-unit-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .tbl-unit-list th { border: 1px solid #000; padding: 5px; background: #ddd; font-weight: bold; }
        .tbl-unit-list td { border: 1px solid #000; padding: 4px 6px; }

        .tbl-metode {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 15px;
        }
        .tbl-metode th, .tbl-metode td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 8pt;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-metode th { background: #ececec; font-weight: bold; }

        .tbl-konfirmasi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .tbl-konfirmasi th { border: 1px solid #000; padding: 6px; background: #ddd; font-weight: bold; text-align: center; }
        .tbl-konfirmasi td { border: 1px solid #000; padding: 6px; vertical-align: middle; }

        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
        }
        .tbl-ttd th { border: 1px solid #000; padding: 6px; background: #ddd; font-weight: bold; text-align: center; }
        .tbl-ttd td { border: 1px solid #000; padding: 6px; vertical-align: top; text-align: center; }
        .sig-image { max-height: 45px; display: block; margin: 0 auto; }
    </style>
</head>

<body>

    @php
        $nama_skema = $nama_skema ?? '';
        $kode_skema = $kode_skema ?? '';
        $tuk = $tuk ?? '';
        $tujuan_asesmen = $tujuan_asesmen ?? '';
        $asesi_options = $asesi_options ?? [];
        $lingkungan = $lingkungan ?? '';
        $peluang_bukti = $peluang_bukti ?? '';
        $hubungan = $hubungan ?? '';
        $pelaku = $pelaku ?? '';
        $benchmark_standards = $benchmark_standards ?? '';
        $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
        
        $karakteristik_khusus = $karakteristik_khusus ?? 'Ada / Tidak Ada*';
        $kebutuhan_kontekstualisasi = $kebutuhan_kontekstualisasi ?? 'Ada / Tidak Ada*';
        $saran = $saran ?? 'Ada / Tidak Ada*';
        $penyesuaian_perangkat = $penyesuaian_perangkat ?? 'Ada / Tidak Ada*';
        $peluang_terintegrasi = $peluang_terintegrasi ?? 'Ada / Tidak Ada*';
        
        $orang_relevan = $orang_relevan ?? [];
        $penyusun = $penyusun ?? [];
        $validator = $validator ?? [];
    @endphp

    <div class="judul-utama">FR.MAPA.01 – MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</div>

    <table class="tbl-skema">
        <tr>
            <td rowspan="2" style="width:25%; font-weight:bold;">Skema Sertifikasi</td>
            <td style="width:10%;">Judul</td>
            <td style="width:3%; text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $kode_skema }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">TUK</td>
            <td style="text-align:center;">:</td>
            <td style="font-weight:bold;">{{ $tuk }}</td>
        </tr>
    </table>

    <table class="tbl-b1">
        <tr>
            <td colspan="3" class="section-header">1. Menentukan Pendekatan Asesmen</td>
        </tr>
        <tr>
            <td class="no-cell">1.1</td>
            <td class="label-cell">Asesi</td>
            <td>
                @php
                    $options = [
                        'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi',
                        'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.',
                        'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi',
                        'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.',
                        'Pelatihan / belajar mandiri atau otodidak.'
                    ];
                @endphp
                @foreach($options as $opt)
                    <div class="cb-row"><span class="{{ in_array($opt, $asesi_options) ? 'cb-on' : 'cb' }}"></span>{{ str_replace([' / ', 'dan / atau'], ['/', 'dan/atau'], $opt) }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="no-cell"></td>
            <td class="label-cell">Tujuan Asesmen</td>
            <td>
                @foreach(['Sertifikasi', 'PKT', 'RPL', 'Lainnya'] as $t)
                    <div class="cb-row"><span class="{{ $tujuan_asesmen == $t ? 'cb-on' : 'cb' }}"></span>{{ $t == 'PKT' ? 'Pengakuan Kompetensi Terkini (PKT)' : ($t == 'RPL' ? 'Rekognisi Pembelajaran Lampau (RPL)' : $t) }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="no-cell"></td>
            <td class="label-cell">Konteks Asesmen</td>
            <td style="padding:0;">
                <table class="inner-tbl">
                    <tr>
                        <td>Lingkungan</td>
                        <td>
                            <span class="{{ $lingkungan == 'Tempat kerja nyata' ? 'cb-on' : 'cb' }}"></span> Tempat kerja nyata &nbsp;&nbsp;
                            <span class="{{ in_array($lingkungan, ['Tempat kerja simulasi', 'Simulasi']) ? 'cb-on' : 'cb' }}"></span> Tempat kerja simulasi
                        </td>
                    </tr>
                    <tr>
                        <td>Peluang pengumpulan bukti</td>
                        <td>
                            <span class="{{ $peluang_bukti == 'Tersedia' ? 'cb-on' : 'cb' }}"></span> Tersedia &nbsp;&nbsp;
                            <span class="{{ $peluang_bukti == 'Terbatas' ? 'cb-on' : 'cb' }}"></span> Terbatas
                        </td>
                    </tr>
                    <tr>
                        <td>Hubungan standar kompetensi</td>
                        <td>
                            <div class="cb-row"><span class="{{ $hubungan == 'Bukti untuk mendukung asesmen' ? 'cb-on' : 'cb' }}"></span> Bukti untuk mendukung asesmen</div>
                            <div class="cb-row"><span class="{{ $hubungan == 'Aktivitas kerja di tempat kerja Asesi' ? 'cb-on' : 'cb' }}"></span> Aktivitas kerja di tempat kerja Asesi</div>
                            <div class="cb-row"><span class="{{ $hubungan == 'Kegiatan Pembelajaran' ? 'cb-on' : 'cb' }}"></span> Kegiatan Pembelajaran</div>
                        </td>
                    </tr>
                    <tr>
                        <td>Siapa yang melakukan asesmen</td>
                        <td>
                            <div class="cb-row"><span class="{{ $pelaku == 'Lembaga Sertifikasi' ? 'cb-on' : 'cb' }}"></span> Lembaga Sertifikasi</div>
                            <div class="cb-row"><span class="{{ $pelaku == 'Organisasi Pelatihan' ? 'cb-on' : 'cb' }}"></span> Organisasi Pelatihan</div>
                            <div class="cb-row"><span class="{{ $pelaku == 'Asesor Perusahaan' ? 'cb-on' : 'cb' }}"></span> Asesor Perusahaan</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="no-cell">1.2</td>
            <td class="label-cell">Standar Industri</td>
            <td>
                @foreach(['Standar Kompetensi: SKKNI', 'Kriteria asesmen dari kurikulum pelatihan', 'Spesifikasi kinerja suatu perusahaan atau industri', 'Spesifikasi Produk', 'Pedoman khusus'] as $b)
                    <div class="cb-row"><span class="{{ $benchmark_standards == $b ? 'cb-on' : 'cb' }}"></span>{{ $b == 'Standar Kompetensi: SKKNI' ? 'Standar Kompetensi' : $b }}</div>
                @endforeach
            </td>
        </tr>
    </table>

    @foreach($kelompok_pekerjaan as $kp)
        <div class="page-break"></div>
        <div style="font-weight:bold; font-size:10pt; margin-bottom:8px; border-bottom:1.5px solid #000; padding-bottom:3px;">2. Mempersiapkan Rencana Asesmen</div>
        <div class="kp-label">Kelompok Pekerjaan {{ $kp['no'] }} — {{ $kp['nama'] }}</div>
        <table class="tbl-unit-list">
            <tr>
                <th style="width:10%;">No.</th>
                <th style="width:30%;">Kode Unit</th>
                <th>Judul Unit</th>
            </tr>
            @foreach($kp['unit'] as $i => $unit)
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td>{{ $unit['kode'] }}</td>
                    <td>{{ $unit['judul'] }}</td>
                </tr>
            @endforeach
        </table>

        <table class="tbl-metode">
            <thead>
                <tr>
                    <th rowspan="2" style="width:25%;">Unit Kompetensi</th>
                    <th rowspan="2" style="width:20%;">Bukti-Bukti</th>
                    <th colspan="3" style="width:12%;">Jenis Bukti</th>
                    <th colspan="4">Metode & Perangkat Asesmen</th>
                </tr>
                <tr>
                    <th>L</th><th>TL</th><th>T</th>
                    <th style="font-size:7pt;">CL</th>
                    <th style="font-size:7pt;">DIT</th>
                    <th style="font-size:7pt;">DPL/DPT/PW</th>
                    <th style="font-size:7pt;">CVP/CRP/VPK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kp['unit'] as $unit)
                    @php
                        $et = (array)($unit['evidence_type'] ?? []);
                        $ms = [];
                        foreach((array)($unit['assessment_methods'] ?? []) as $m) if(!empty($m['answer'])) $ms[] = $m['answer'];
                    @endphp
                    <tr>
                        <td style="text-align:left;">{{ $unit['judul'] }}</td>
                        <td style="text-align:left; font-size:7pt;">{{ $unit['evidence'] ?? '' }}</td>
                        <td><span class="{{ in_array('L', $et) ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ in_array('TL', $et) ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ in_array('T', $et) ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ in_array('CL', $ms) ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ in_array('DIT', $ms) ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ count(array_intersect(['DPL','DPT','PW'], $ms)) > 0 ? 'cb-on' : 'cb' }}"></span></td>
                        <td><span class="{{ count(array_intersect(['CVP','CRP','VPK'], $ms)) > 0 ? 'cb-on' : 'cb' }}"></span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="page-break"></div>
    <div style="font-weight:bold; font-size:10pt; margin-bottom:10px; border-bottom:1.5px solid #000; padding-bottom:3px;">3. Persyaratan Modifikasi dan Kontekstualisasi</div>
    <table class="tbl-b1">
        <tr>
            <td style="width:40%; font-weight:bold;">3.1 a. Karakteristik Kandidat</td>
            <td>{{ $karakteristik_khusus }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold; padding-left:20px;">b. Kebutuhan kontekstualisasi</td>
            <td>{{ $kebutuhan_kontekstualisasi }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">3.2 Saran dari paket pelatihan</td>
            <td>{{ $saran }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">3.3 Penyesuaian perangkat asesmen</td>
            <td>{{ $penyesuaian_perangkat }}</td>
        </tr>
        <tr>
            <td style="font-weight:bold;">3.4 Peluang kegiatan terintegrasi</td>
            <td>{{ $peluang_terintegrasi }}</td>
        </tr>
    </table>

    <div style="font-weight:bold; margin-bottom:5px;">Konfirmasi dengan Orang yang Relevan</div>
    <table class="tbl-konfirmasi">
        <thead>
            <tr>
                <th style="width:45%;">Orang yang Relevan</th>
                <th style="width:30%;">Nama</th>
                <th style="width:25%;">Tandatangan & Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orang_relevan as $o)
                <tr>
                    <td>{{ $o['role'] }}</td>
                    <td style="text-align:center;">{{ $o['nama'] }}</td>
                    <td style="text-align:center;">
                        @if($o['signature'])
                            <img src="{{ $o['signature'] }}" class="sig-image">
                        @endif
                        <div style="font-size:8pt;">{{ $o['date'] }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="font-weight:bold; margin-bottom:5px;">Penyusun dan Validator</div>
    <table class="tbl-ttd">
        <thead>
            <tr>
                <th style="width:15%;">Status</th>
                <th style="width:5%;">No</th>
                <th style="width:30%;">Nama</th>
                <th style="width:20%;">Nomor MET</th>
                <th style="width:30%;">Tanda Tangan & Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['PENYUSUN' => $penyusun, 'VALIDATOR' => $validator] as $status => $items)
                @foreach($items as $idx => $p)
                <tr>
                    @if($idx === 0) <td rowspan="2" style="font-weight:bold; vertical-align:middle;">{{ $status }}</td> @endif
                    <td style="text-align:center;">{{ $idx + 1 }}</td>
                    <td>{{ $p['nama'] ?? '' }}</td>
                    <td>{{ $p['nomor_met'] ?? '' }}</td>
                    <td style="height:65px; vertical-align:middle;">
                        @if($p['qr_code'] ?? null)
                            <img src="{{ $p['qr_code'] }}" style="width:45px; height:45px; vertical-align:middle;">
                        @elseif($p['signature'] ?? null)
                            <img src="{{ $p['signature'] }}" class="sig-image" style="display:inline-block;">
                        @endif
                        <span style="font-size:8pt; margin-left:5px;">{{ $p['date'] ?? '' }}</span>
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>