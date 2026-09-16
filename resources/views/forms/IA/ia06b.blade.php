{{-- resources/views/forms/IA/ia06b.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.06B - Lembar Kunci Jawaban Esai</title>
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
        .jawaban-area {
            border:1px solid #000;
            padding:10px;
            min-height:280px;
            word-wrap:break-word;
        }
        .jawaban-area ol li { margin-bottom:35px; }
        .pv-table { width:100%; border-collapse:collapse; table-layout:fixed; margin-top:20px; }
        .pv-table th, .pv-table td { border:1px solid #000; padding:4px 6px; text-align:center; word-wrap:break-word; vertical-align:middle; }
        .pv-table th { background:#e0e0e0; font-weight:bold; }
    </style>
</head>
<body>

<div class="judul">FR.IA.06.B. LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS ESAI</div>

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
        <td style="font-weight:bold;" colspan="5">{{ $nama_skema ?? '' }}</td>
    </tr>
    <tr>
        <td>Nomor :</td>
        <td style="font-weight:bold;" colspan="5">{{ $kode_skema ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3">TUK :</td>
        <td colspan="5">{{ $tuk }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesor :</td>
        <td colspan="5">{{ $nama_asesor ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3">Nama Asesi :</td>
        <td colspan="5">{{ $nama_asesi ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3">Tanggal :</td>
        <td colspan="5">{{ $tanggal ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3">Waktu :</td>
        <td colspan="5">{{ $waktu ?? '-' }}</td>
    </tr>
</table>

<div style="margin:15px 0 8px 0; font-weight:bold;">Kunci Jawaban:</div>

<table class="tbl" style="margin-bottom: 20px;">
    <thead>
        <tr style="background: #f0f0f0; font-weight: bold;">
            <td style="width: 8%; text-align: center;">No.</td>
            <td style="text-align: center;">Kunci Jawaban:</td>
        </tr>
    </thead>
    <tbody>
        @if(isset($kunci_jawaban) && count($kunci_jawaban) > 0)
            @foreach($kunci_jawaban as $index => $kj)
                <tr>
                    <td style="text-align: center; vertical-align: top;">{{ $index + 1 }}.</td>
                    <td style="min-height: 60px; vertical-align: top;">{!! nl2br(e($kj['jawaban'] ?? '')) !!}</td>
                </tr>
            @endforeach
        @else
            @for ($i = 1; $i <= 5; $i++)
                <tr>
                    <td style="text-align: center; height: 60px;">{{ $i }}.</td>
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
