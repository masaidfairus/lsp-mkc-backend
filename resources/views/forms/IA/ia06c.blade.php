{{-- resources/views/forms/IA/ia06c.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FR.IA.06C - Lembar Jawaban Esai</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 4rem;
        }
        .judul { font-size: 10pt; font-weight: bold; margin-bottom: 8px; text-align:start; }
        .tbl { width:100%; border-collapse:collapse; table-layout:fixed; margin-bottom:12px; }
        .tbl td { border:1px solid #000; padding:5px 7px; vertical-align:middle; word-wrap:break-word; }
        .tbl .header-val { font-weight:bold; }
        .jawaban-table {
            width:100%; border-collapse:collapse; table-layout:fixed; margin:15px 0 20px 0;
        }
        .jawaban-table th, .jawaban-table td {
            border:1px solid #000; padding:6px 8px; vertical-align:middle; word-wrap:break-word;
        }
        .jawaban-table .no-col { width:8%; }
        .jawaban-table .jawab-col { width:62%; }
        .umpan-box {
            border:1px solid #000; padding:8px; min-height:80px; margin-bottom:20px; word-wrap:break-word;
        }
        .ttd-table { width:100%; border-collapse:collapse; table-layout:fixed; }
        .ttd-table td { border:1px solid #000; padding:6px 8px; vertical-align:top; word-wrap:break-word; }
        .ttd-header { font-weight:bold; background:#e8e8e8; }
        .pv-table { width:100%; border-collapse:collapse; table-layout:fixed; margin-top:20px; }
        .pv-table th, .pv-table td { border:1px solid #000; padding:4px 6px; text-align:center; word-wrap:break-word; vertical-align:middle; }
        .pv-table th { background:#e0e0e0; font-weight:bold; }
    </style>
</head>
<body>

<div class="judul">FR.IA.06.C. LEMBAR JAWABAN PERTANYAAN TERTULIS ESAI</div>

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

<table class="jawaban-table">
    <thead>
        <tr style="background: #f0f0f0;">
            <th class="no-col" rowspan="2">No.</th>
            <th class="jawab-col" rowspan="2">Jawaban:</th>
            <th colspan="2">Pencapaian</th>
        </tr>
        <tr style="background: #f0f0f0;">
            <th style="width:12%">Ya</th>
            <th style="width:12%">Tidak</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($jawaban_data) && count($jawaban_data) > 0)
            @foreach($jawaban_data as $j)
                <tr>
                    <td style="text-align: center; vertical-align: top;">{{ $j['no'] }}</td>
                    <td style="min-height:70px; vertical-align: top;">{!! nl2br(e($j['jawaban_asesi'] ?? '')) !!}</td>
                    <td style="text-align:center; vertical-align: middle;">
                        <span class="{!! ($j['is_yes'] ?? false) ? 'cb-checked' : 'cb' !!}"></span>
                    </td>
                    <td style="text-align:center; vertical-align: middle;">
                        <span class="{!! ($j['is_no'] ?? false) ? 'cb-checked' : 'cb' !!}"></span>
                    </td>
                </tr>
            @endforeach
        @else
            @for ($i = 1; $i <= 5; $i++)
            <tr>
                <td style="text-align: center; height:70px;">{{ $i }}.</td>
                <td></td>
                <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">&#9744;</td>
                <td style="text-align:center; font-family: DejaVu Sans, sans-serif;">&#9744;</td>
            </tr>
            @endfor
        @endif
    </tbody>
</table>

<div class="umpan-box">
    <strong>Umpan balik untuk asesi :</strong><br>
    {{ $umpan_balik ?: '...' }}
    <br><br>
    <strong>Aspek pengetahuan seluruh unit pada kelompok pekerjaan yang diujikan:</strong> {{ ($aspect_status ?? '') == 'tercapai' ? 'Tercapai' : 'Belum Tercapai' }}
    <br><br>
    <strong>Unit / Elemen / KUK yang belum tercapai:</strong><br>
    {{ $unachieved ?: '-' }}
</div>

<div style="page-break-inside: avoid;">
    <table class="ttd-table" style="border: 1px solid #000;">
        <tr>
            <td colspan="3" style="font-weight:bold; border:1px solid #000; padding:8px; background:#f0f0f0;">Asesi :</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px; width: 30%;">Nama</td>
            <td style="text-align:center; border:1px solid #000; padding:8px; width: 3%;">:</td>
            <td style="border:1px solid #000; padding:8px;">{{ $nama_asesi }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px;">Tanda tangan/Tanggal</td>
            <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
            <td style="border:1px solid #000; padding:8px;">
                <div class="sig-block">
                    @if($asesi_signature)
                        <img src="{{ $asesi_signature }}" class="sig-image" style="max-height: 50px;">
                    @endif
                    <div style="font-size: 8.5pt;">{{ $tanggal }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="font-weight:bold; border:1px solid #000; padding:8px; background:#f0f0f0;">Asesor :</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px;">Nama</td>
            <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
            <td style="border:1px solid #000; padding:8px;">{{ $nama_asesor }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px;">No. Reg</td>
            <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
            <td style="border:1px solid #000; padding:8px;">{{ $asesor_reg ?? '' }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #000; padding:8px;">Tanda tangan/Tanggal</td>
            <td style="text-align:center; border:1px solid #000; padding:8px;">:</td>
            <td style="border:1px solid #000; padding:8px;">
                <div class="sig-block">
                    @if($asesor_qr_code)
                        <img src="{{ $asesor_qr_code }}" class="sig-image" style="width: 50px; height: 50px;">
                    @elseif($asesor_signature)
                        <img src="{{ $asesor_signature }}" class="sig-image" style="max-height: 50px;">
                    @endif
                    <div style="font-size: 8.5pt;">{{ $tanggal }}</div>
                </div>
            </td>
        </tr>
    </table>
</div>

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
