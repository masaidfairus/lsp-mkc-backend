{{-- resources/views/forms/AK/ak02.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.AK.02 - Rekaman Asesmen Kompetensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem
        }

        .page {
            width: 100%;
        }

        .page-break {
            page-break-before: always;
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

        .header-table .val {
            font-weight: bold;
        }

        .instruksi {
            font-size: 9pt;
            margin-bottom: 10px;
            text-align: justify;
        }

        /* ── Tabel Penilaian ── */
        .penilaian-table {
            width: 99.8%;
            border-collapse: collapse;
            margin-bottom: 0;
            table-layout: fixed;
        }

        .penilaian-table th:last-child,
        .penilaian-table td:last-child {
            border-right: 1px solid #000 !important;
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
        .col-no {
            width: 5%;
        }

        .col-unit {
            width: 33%;
            text-align: left;
            padding: 4px 6px;
        }

        .col-metode {
            width: 9%;
        }

        .col-lainnya {
            width: 8%;
        }

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
            position: relative;
        }

        .checkbox.checked::after {
            content: '';
            position: absolute;
            left: 2px;
            top: -1px;
            width: 3px;
            height: 7px;
            border: solid black;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
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

        <div class="form-title">FR.AK.02. REKAMAN ASESMEN KOMPETENSI</div>

        <div class="instruksi">
            <strong>Instruksi:</strong> Beri tanda centang (√) pada kolom yang sesuai untuk setiap unit kompetensi
            berdasarkan hasil asesmen.
        </div>

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td style="width:26%" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
                <td style="width:10%">Judul</td>
                <td style="width:3%; text-align:center">:</td>
                <td class="val">{{ $scheme_name }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td style="text-align:center">:</td>
                <td class="val">{{ $scheme_code }}</td>
            </tr>
            <tr>
                <td colspan="2">TUK</td>
                <td style="text-align:center">:</td>
                <td>{{ $tuk }}</td>
            </tr>
            <tr>
                <td colspan="2">Nama Asesor</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesor_name }}</td>
            </tr>
            <tr>
                <td colspan="2">Nama Asesi</td>
                <td style="text-align:center">:</td>
                <td>{{ $asesi_name }}</td>
            </tr>
            <tr>
                <td rowspan="2">Tanggal Asesmen</td>
                <td>Mulai</td>
                <td style="text-align:center">:</td>
                <td>{{ $assessment_date }}</td>
            </tr>
            <tr>
                <td>Selesai</td>
                <td style="text-align:center">:</td>
                <td>{{ $assessment_date }}</td>
            </tr>
        </table>

        {{-- TABEL PENILAIAN --}}
        <table class="penilaian-table">
            <thead>
                <tr>
                    <th class="col-no">No.</th>
                    <th class="col-unit" style="text-align:center;">Unit Kompetensi</th>
                    <th class="col-metode" style="font-size: 8px;">Observasi/<br>Demonstrasi</th>
                    <th class="col-metode" style="font-size: 8px;">Portofolio</th>
                    <th class="col-metode" style="font-size: 8px;">Pernyataan<br>Pihak Ketiga/<br>Wawancara</th>
                    <th class="col-metode" style="font-size: 8px;">Pertanyaan<br>Lisan</th>
                    <th class="col-metode" style="font-size: 8px;">Pertanyaan<br>Tertulis</th>
                    <th class="col-metode" style="font-size: 8px;">Proyek<br>Kerja</th>
                    <th class="col-lainnya">Lainnya</th>
                </tr>
            </thead>
            <tbody>
                {{-- Unit Kompetensi --}}
                @foreach ($competency_units as $index => $u)
                    @php $ev = $u['evidence_methods'] ?? []; @endphp
                    <tr class="unit-row">
                        <td style="text-align:center">{{ $index + 1 }}.</td>
                        <td class="col-unit">{{ $u['unit_title'] ?? '-' }}</td>
                        <td class="td-center">{{ !empty($ev['observasi_demonstrasi']) ? '✓' : '' }}</td>
                        <td class="td-center">{{ !empty($ev['portofolio']) ? '✓' : '' }}</td>
                        <td class="td-center">
                            {{ (!empty($ev['pernyataan_pihak_ketiga']) || !empty($ev['pertanyaan_wawancara'])) ? '✓' : '' }}
                        </td>
                        <td class="td-center">{{ !empty($ev['pertanyaan_lisan']) ? '✓' : '' }}</td>
                        <td class="td-center">{{ !empty($ev['pertanyaan_tertulis']) ? '✓' : '' }}</td>
                        <td class="td-center">{{ !empty($ev['proyek_kerja']) ? '✓' : '' }}</td>
                        <td class="td-center">{{ !empty($ev['lainnya']) ? '✓' : '' }}</td>
                    </tr>
                @endforeach

                {{-- Rekomendasi --}}
                <tr class="rekomendasi-row">
                    <td colspan="2" style="font-weight:bold; text-align:left; padding: 5px 7px;">
                        Rekomendasi hasil asesmen
                    </td>
                    <td colspan="7" style="text-align:left; padding: 5px 10px;">
                        <span
                            class="checkbox {{ ($assessment_summary['is_competent'] ?? null) === true ? 'checked' : '' }}"></span>
                        Kompeten
                        &nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;
                        <span
                            class="checkbox {{ ($assessment_summary['is_competent'] ?? null) === false ? 'checked' : '' }}"></span>
                        Belum kompeten
                    </td>
                </tr>

                {{-- Tindak Lanjut --}}
                <tr class="tindak-row">
                    <td colspan="2" style="text-align:left; vertical-align:top; padding: 5px 7px;">
                        <strong>Tindak lanjut yang dibutuhkan</strong><br>
                        <span style="font-size:8.5pt">(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk
                            mencapai kompetensi)</span>
                    </td>
                    <td colspan="7" style="text-align:left; padding: 5px 7px; vertical-align:top">
                        {{ $assessment_summary['follow_up_action'] ?? '' }}
                    </td>
                </tr>

                {{-- Komentar / Observasi --}}
                <tr class="komentar-row">
                    <td colspan="2" style="text-align:left; padding: 5px 7px;">
                        Komentar/ Observasi oleh asesor
                    </td>
                    <td colspan="7" style="text-align:left; padding: 5px 7px; vertical-align:top">
                        {{ $assessment_summary['assessor_comment'] ?? '' }}
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="page-break"></div>

        {{-- TTD --}}
        <table class="penilaian-table" style="border-top:none; margin-top: -1px;">
            <tr>
                <td
                    style="width: 55%; border-right: none; height: 80px; vertical-align: middle; padding: 10px; text-align: left;">
                    Tanda tangan Asesor :
                    @if($asesor_qr_code)
                        <img src="{{ $asesor_qr_code }}"
                            style="width:50px; height:50px; vertical-align: middle; margin-left: 5px;">
                    @endif
                    <div style="font-size: 8pt; margin-top: 5px;">Nama: {{ $asesor_name }} / No. Reg:
                        {{ $asesor_reg ?? '-' }}</div>
                </td>
                <td style="width: 45%; border-left: none; vertical-align: middle; padding: 10px; text-align: left;">
                    Tanggal : {{ $signatures['assessor']['date'] ?? '' }}
                </td>
            </tr>
            <tr>
                <td
                    style="width: 55%; border-right: none; height: 60px; vertical-align: middle; padding: 10px; text-align: left;">
                    Tanda tangan Asesi : {{ $asesi_name }}
                    <div style="font-size: 7.5pt; font-style: italic; color: #666; margin-top: 4px;">
                        (Tanda tangan digital tersemat via aplikasi mobile)
                    </div>
                </td>
                <td style="width: 45%; border-left: none; vertical-align: middle; padding: 10px; text-align: left;">
                    Tanggal : {{ $signatures['assessee']['date'] ?? '' }}
                </td>
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