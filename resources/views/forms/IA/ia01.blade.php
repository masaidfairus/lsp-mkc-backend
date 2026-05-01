<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.IA.01</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            padding: 4rem;
        }

        @page {
            margin: 1.8cm 2cm 1.8cm 2cm;
            size: A4 portrait;
        }

        .page-break {
            page-break-before: always;
        }

        .judul {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .tbl,
        .tbl-kp,
        .tbl-unit-header,
        .tbl-kuk,
        .tbl-footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }

        .tbl td,
        .tbl-kp td,
        .tbl-unit-header td,
        .tbl-kuk td,
        .tbl-footer td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .tbl-kp th,
        .tbl-kuk th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .cb,
        .cb-checked {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            vertical-align: middle;
            position: relative;
        }

        .cb-checked::after {
            content: '';
            position: absolute;
            left: 2px;
            top: 0;
            width: 3px;
            height: 6px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .umpan-box {
            border: 1px solid #000;
            padding: 5px 7px;
            min-height: 40px;
            margin-bottom: 10px;
            font-size: 8pt;
        }

        .ttd-img {
            max-height: 85px;
            max-width: 220px;
            image-rendering: crisp-edges;
        }

          .page-break { page-break-before: always; }

        .judul { font-size: 10pt; font-weight: bold; margin-bottom: 8px; }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 9pt;
            line-height: 1.4;
        }

        .tbl-kp {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .tbl-kp th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-kp td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin: 10px 0 6px 0;
        }

        .petunjuk-list {
            padding-left: 20px;
            margin-bottom: 12px;
        }
        .petunjuk-list li {
            margin-bottom: 3px;
            font-size: 9pt;
            line-height: 1.4;
        }

        .field-line {
            font-size: 9pt;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .field-space {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px solid #000;
            margin-left: 6px;
            vertical-align: bottom;
        }

        .perlengkapan-list {
            padding-left: 22px;
            margin: 3px 0 8px 0;
        }
        .perlengkapan-list li {
            font-size: 9pt;
            margin-bottom: 2px;
        }

        /* Footer TTD */
        .tbl-ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
            margin-bottom: 14px;
        }
        .tbl-ttd td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }
        .tbl-ttd .header-cell {
            font-weight: bold;
            background: #f0f0f0;
        }
        .tbl-ttd .ttd-space { height: 35px; }

        /* Penyusun validator */
        .tbl-pv {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
        }
        .tbl-pv th {
            border: 1px solid #000;
            padding: 4px 6px;
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        .tbl-pv td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            text-align: center;
        }
        .tbl-pv td.ttd-space { height: 40px; }
    </style>
</head>

<body>

    @php
        $nama_skema = $nama_skema ?? '';
        $kode_skema = $kode_skema ?? '';
        $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri';
        $nama_asesor = $nama_asesor ?? '';
        $nama_asesi = $nama_asesi ?? '';
        $tanggal = $tanggal ?? '';
        $is_competent = $is_competent ?? null;
        $not_competent_detail = $not_competent_detail ?? [];
        $asesi_name = $asesi_name ?? '';
        $asesi_date = $asesi_date ?? '';
        $asesi_signature = $asesi_signature ?? null;
        $asesor_name = $asesor_name ?? '';
        $asesor_reg = $asesor_reg ?? '';
        $asesor_date = $asesor_date ?? '';
        $asesor_signature = $asesor_signature ?? null;
        $asesor_qr_code = $asesor_qr_code ?? null;
        $kelompok_pekerjaan = $kelompok_pekerjaan ?? [];
    @endphp

    {{-- JUDUL --}}
    <div class="judul">FR.IA.01. CL – CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</div>

    {{-- HEADER INFO --}}
<!-- Header -->
    <table class="tbl">
        <colgroup>
            <col style="width:28%;">
            <col style="width:14%;">
            <col style="width:3%;">
            <col style="width:55%;">
        </colgroup>
        <tr>
            <td rowspan="2" style="vertical-align:middle;" colspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
            <td>Judul :</td>
            <td style="font-weight:bold;" colspan="5">{{ $nama_skema }}</td>
        </tr>
        <tr>
            <td>Nomor :</td>
            <td style="font-weight:bold;" colspan="5">{{ $kode_skema }}</td>
        </tr>
        <tr>
            <td colspan="3">TUK :</td>
            <td colspan="5">{{ $tuk }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesor :</td>
            <td colspan="5">{{ $nama_asesor }}</td>
        </tr>
        <tr>
            <td colspan="3">Nama Asesi :</td>
            <td colspan="5">{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td colspan="3">Tanggal :</td>
            <td colspan="5">{{ $tanggal }}</td>
        </tr>
    </table>

    {{-- PANDUAN --}}
    <div class="panduan-box" style="margin-bottom: 1rem;">
        <div class="panduan-title">PANDUAN BAGI ASESOR</div>
        <div class="panduan-body">
            <div class="panduan-item">Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam
                tabel.</div>
            <div class="panduan-item">Isilah standar industri atau tempat kerja.</div>
            <div class="panduan-item">Penilaian Lanjut diisi bila hasil belum dapat disimpulkan.</div>
            <div class="panduan-item">Isilah kolom KUK sesuai dengan Unit Kompetensi/SKKNI.</div>
        </div>
    </div>

    {{-- LOOP KELOMPOK PEKERJAAN --}}
    @foreach($kelompok_pekerjaan as $kp)
        @if(!$loop->first)
            <div class="page-break"></div>
        @endif

        {{-- Tabel daftar unit --}}
        <table class="tbl-kp">
            <colgroup>
                <col style="width:18%;">
                <col style="width:5%;">
                <col style="width:20%;">
                <col style="width:57%;">
            </colgroup>
            <tr>
                <th colspan="2">Kelompok Pekerjaan {{ $kp['no'] }}</th>
                <th>No.</th>
                <th colspan="2">Kode Unit</th>
                <th colspan="3" style="text-align:left; padding-left:8px;">Judul Unit</th>
            </tr>
            @foreach($kp['unit'] as $i => $unit)
                <tr>
                    @if($i === 0)
                        <td colspan="2" rowspan="{{ count($kp['unit']) }}"
                            style="vertical-align:middle; text-align:center; font-size:8pt;">{{ $kp['nama'] }}</td>
                    @endif
                    <td style="text-align:center;">{{ $i + 1 }}.</td>
                    <td colspan="2">{{ $unit['kode'] }}</td>
                    <td colspan="3">{{ $unit['judul'] }}</td>
                </tr>
            @endforeach
        </table>

        {{-- Per unit --}}
        @foreach($kp['unit'] as $ui => $unit)
            @if($ui > 0)
                <div class="page-break"></div>
            @endif

            {{-- Header unit --}}
            <table class="tbl-unit-header" style="margin-bottom:0;">
                <colgroup>
                    <col style="width:22%;">
                    <col style="width:12%;">
                    <col style="width:3%;">
                    <col style="width:63%;">
                </colgroup>
                <tr>
                    <td colspan="2" rowspan="2" style="vertical-align:middle; font-weight:bold;">Unit Kompetensi
                        {{ ($kp['unit_offset'] ?? 0) + $ui + 1 }}</td>
                    <td>Kode Unit</td>
                    <td style="text-align:center;">:</td>
                    <td colspan="3">{{ $unit['kode'] }}</td>
                </tr>
                <tr>
                    <td>Judul Unit</td>
                    <td style="text-align:center;">:</td>
                    <td colspan="3">{{ $unit['judul'] }}</td>
                </tr>
            </table>

            {{-- Tabel KUK --}}
            <table class="tbl-kuk">
                <colgroup>
                    <col style="width:20px;">
                    <col style="width:18%;">
                    <col style="width:35%;">
                    <col style="width:17%;">
                    <col style="width:6%;">
                    <col style="width:6%;">
                    <col style="width:10%;">
                </colgroup>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2" colspan="2">Elemen</th>
                    <th rowspan="2" colspan="2">Kriteria Unjuk Kerja</th>
                    <th rowspan="2">Standar Industri atau Tempat Kerja</th>
                    <th colspan="2">Pencapaian</th>
                    <th rowspan="2">Penilaian Lanjut</th>
                </tr>
                <tr>
                    <th>Ya</th>
                    <th>Tidak</th>
                </tr>
                @php $elNo = 0; @endphp
                @foreach($unit['elemen'] as $el)
                    @php $elNo++;
                    $kukCount = count($el['kuk']); @endphp
                    @foreach($el['kuk'] as $ki => $kuk)
                        @php
                            $isLast = ($ki === $kukCount - 1);
                            $kukText = is_array($kuk) ? ($kuk['kuk'] ?? '') : $kuk;
                            $stdIndustri = is_array($kuk) ? ($kuk['industry_standard'] ?? '') : '';
                            $isCompetent = is_array($kuk) ? ($kuk['achievement_is_competent'] ?? null) : null;
                            $penilaian = is_array($kuk) ? ($kuk['further_assessment_notes'] ?? '') : '';
                        @endphp
                        <tr>
                            <td class="no" style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                                @if($ki === 0){{ $elNo }}.@endif</td>
                            <td colspan="2" style="vertical-align:top; border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">
                                @if($ki === 0){{ $el['nama'] }}@endif</td>
                            <td colspan="2">{{ $kukText }}</td>
                            <td>{{ $stdIndustri }}</td>
                            <td class="center"><span class="{{ $isCompetent === true ? 'cb-checked' : 'cb' }}"></span></td>
                            <td class="center"><span class="{{ $isCompetent === false ? 'cb-checked' : 'cb' }}"></span></td>
                            <td style="border-bottom: {{ $isLast ? '1px solid #000' : 'none' }};">{{ $penilaian }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>

            {{-- Umpan balik --}}
            <div class="umpan-box">Umpan balik bagi Asesi: {{ $kp['umpan_balik'] ?? '' }}</div>
        @endforeach
    @endforeach

    {{-- REKOMENDASI + TTD --}}
    <div class="page-break"></div>
    <table class="tbl-footer">
        <colgroup>
            <col style="width:48%;">
            <col style="width:52%;">
        </colgroup>
        <tr>
            <td style="vertical-align:top;">
                <div style="font-weight:bold; margin-bottom:6px;">Rekomendasi:</div>
                <div style="margin-bottom:6px;">
                    <span class="{{ $is_competent === true ? 'cb-checked' : 'cb' }}"></span>
                    &nbsp; Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan
                    <strong>KOMPETEN</strong>
                </div>
                <div style="margin-bottom:10px;">
                    <span class="{{ $is_competent === false ? 'cb-checked' : 'cb' }}"></span>
                    &nbsp; Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <strong>BELUM
                        KOMPETEN</strong>
                </div>
            </td>
            <td style="vertical-align:top; padding:0;">
                <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
                    <colgroup>
                        <col style="width:105px;">
                        <col style="width:16px;">
                        <col>
                    </colgroup>

                    {{-- ASESI --}}
                    <tr>
                        <td colspan="4" style="font-weight:bold; padding:4px 7px; border-bottom:1px solid #000;">Asesi
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:4px 7px; white-space:nowrap; border-right:1px solid #000;">Nama
                        </td>
                        <td colspan="2" style="padding:4px 7px;">{{ $asesi_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            style="padding:4px 7px; height:70px; vertical-align:top; white-space:nowrap; border-right:1px solid #000;">
                            Tanda Tangan / Tanggal</td>                        <td colspan="2" style="padding:4px 7px; vertical-align:middle;">
                            @if($asesi_signature)
                                <img src="{{ $asesi_signature }}" class="ttd-img"><br>
                                {{ $asesi_date }}
                            @endif
                        </td>
                    </tr>

                    {{-- ASESOR --}}
                    <tr>
                        <td colspan="4" style="font-weight:bold; padding:4px 7px; border-bottom:1px solid #000;">Asesor
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:4px 7px; white-space:nowrap; border-right:1px solid #000;">Nama
                        </td>
                        <td colspan="2" style="padding:4px 7px;">{{ $asesor_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:4px 7px; white-space:nowrap; border-right:1px solid #000;">No.
                            Reg</td>
                        <td colspan="2" style="padding:4px 7px;">{{ $asesor_reg }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            style="padding:4px 7px; height:80px; vertical-align:middle; white-space:nowrap; border-right:1px solid #000;">
                            Tanda Tangan / Tanggal</td>
                        <td colspan="2" style="padding:4px 7px; vertical-align:middle;">
                            @if($asesor_qr_code)
                                <img src="{{ $asesor_qr_code }}" style="width: 50px; height: 50px; vertical-align: middle;">
                                <span style="vertical-align: middle; margin-left: 10px;">
                                    {{ $asesor_date }}
                                </span>
                            @elseif($asesor_signature ?? null)
                                <img src="{{ $asesor_signature }}" style="max-height: 50px; vertical-align: middle;">
                                <span style="vertical-align: middle; margin-left: 10px;">
                                    {{ $asesor_date }}
                                </span>
                            @endif
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>