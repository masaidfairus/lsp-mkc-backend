{{-- resources/views/forms/IA/ia06a.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.06A - Daftar Pertanyaan Tertulis Esai</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
        }
        .judul { font-size: 10pt; font-weight: bold; margin-bottom: 10px; text-align:start; }
        .tbl { width:100%; border-collapse:collapse; table-layout:fixed; margin-bottom:12px; }
        .tbl td { border:1px solid #000; padding:5px 7px; vertical-align:middle; word-wrap:break-word; }
        .tbl .header-val { font-weight:bold; }
        .soal-area {
            border:1px solid #000;
            padding:10px;
            margin:15px 0;
            min-height:320px;
            word-wrap:break-word;
        }
        .soal-area ol { padding-left:20px; }
        .soal-area li { margin-bottom:35px; }
        .pv-table { width:100%; border-collapse:collapse; table-layout:fixed; margin-top:20px; page-break-inside: avoid; }
        .pv-table th, .pv-table td { border:1px solid #000; padding:4px 6px; text-align:center; word-wrap:break-word; vertical-align:middle; }
        .pv-table th { background:#e0e0e0; font-weight:bold; }
        .sig-block {
            text-align: left;
        }
        .sig-image {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
@php
    $nama_skema = $nama_skema ?? '';
    $kode_skema = $kode_skema ?? '';
    $tuk = $tuk ?? 'Sewaktu/Tempat Kerja/Mandiri*';
    $nama_asesor = $nama_asesor ?? '';
    $nama_asesi = $nama_asesi ?? '';
    $tanggal = $tanggal ?? '';
    $penyusun = $penyusun ?? [];
    $validator = $validator ?? [];
@endphp

<div class="judul">FR.IA.06A. DPT – DAFTAR PERTANYAAN TERTULIS ESAI</div>

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
        <tr>
            <td colspan="3">Waktu :</td>
            <td colspan="5">{{ $waktu ?? '-' }}</td>
        </tr>
    </table>

<div style="margin:15px 0 8px 0; font-weight:bold;">Jawablah semua pertanyaan di bawah ini:</div>

<table class="tbl" style="margin-bottom: 20px;">
    <thead>
        <tr style="background: #f0f0f0; font-weight: bold;">
            <td style="width: 8%; text-align: center;">No.</td>
            <td style="text-align: center;">Pertanyaan:</td>
        </tr>
    </thead>
    <tbody>
        @if(isset($soal) && count($soal) > 0)
            @foreach($soal as $index => $s)
                <tr>
                    <td style="text-align: center; vertical-align: top;">{{ $index + 1 }}.</td>
                    <td style="min-height: 50px; vertical-align: top;">{{ $s['pertanyaan'] ?? '' }}</td>
                </tr>
            @endforeach
        @else
            @for ($i = 1; $i <= 5; $i++)
                <tr>
                    <td style="text-align: center; height: 50px;">{{ $i }}.</td>
                    <td></td>
                </tr>
            @endfor
        @endif
    </tbody>
</table>

<div style="page-break-inside: avoid;">
    <div style="font-weight:bold; margin:20px 0 8px 0;">PENYUSUN DAN VALIDATOR</div>
    <table class="pv-table">
        <colgroup>
            <col style="width:18%;">
            <col style="width:7%;">
            <col style="width:35%;">
            <col style="width:20%;">
            <col style="width:20%;">
        </colgroup>
        <thead>
            <tr>
                <th colspan="2">STATUS</th>
                <th>NO</th>
                <th>NAMA</th>
                <th>NOMOR MET</th>
                <th>TANDA TANGAN DAN TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['Penyusun' => $compilers ?? $penyusun ?? [], 'Validator' => $validators ?? $validator ?? []] as $status => $items)
                @php 
                    $items = (array)$items;
                    $count = max(2, count($items)); 
                @endphp
                @for($i = 0; $i < $count; $i++)
                    @php
                        $item = $items[$i] ?? [];
                        $name = $item['name'] ?? $item['nama'] ?? '';
                        $reg = $item['reg_number'] ?? $item['nomor_met'] ?? '';
                        $qr = $item['qr_code'] ?? null;
                        $sig = $item['signature'] ?? null;
                        $date = $item['date'] ?? '';
                    @endphp
                    <tr>
                        @if($i === 0)
                            <td colspan="2" rowspan="{{ $count }}" style="vertical-align:middle; font-weight:bold; text-transform:uppercase;">{{ $status }}</td>
                        @endif
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $reg }}</td>
                        <td style="height:55px; vertical-align: middle;">
                            <div style="text-align:center;">
                                @if($qr)
                                    <img src="{{ $qr }}" style="width:45px;height:45px;display:inline-block;">
                                @elseif($sig)
                                    <img src="{{ $sig }}" style="max-height:40px;display:inline-block;">
                                @endif
                                @if($date)
                                    <div style="font-size:8pt; margin-top:2px;">{{ $date }}</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>