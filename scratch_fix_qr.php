<?php

// 1. Controller Updates
$dirPath = 'app/Http/Controllers/PDF';
$files = scandir($dirPath);

foreach ($files as $filename) {
    if (preg_match('/^PdfIa.*Controller\.php$/', $filename)) {
        $filepath = $dirPath . '/' . $filename;
        $content = file_get_contents($filepath);
        $original = $content;

        // Make sure we map 'compilers' and 'validators'
        if (strpos($content, "'compilers'") === false) {
            $content = str_replace(
                "'penyusun'         => \$mainData['penyusun'] ?? [],",
                "'compilers'        => \$mainData['compilers'] ?? [],\n            'penyusun'         => \$mainData['compilers'] ?? [],",
                $content
            );
            $content = str_replace(
                "'validator'        => \$mainData['validator'] ?? [],",
                "'validators'       => \$mainData['validators'] ?? [],\n            'validator'        => \$mainData['validators'] ?? [],",
                $content
            );
        }

        if ($content !== $original) {
            file_put_contents($filepath, $content);
            echo "Updated Controller: {$filename}\n";
        }
    }
}

// 2. Blade Template Updates
$bladeDir = 'resources/views/forms/IA';
$bladeFiles = scandir($bladeDir);

$dynamicTableCode = <<<HTML
        @foreach(['Penyusun' => \$compilers ?? \$penyusun ?? [], 'Validator' => \$validators ?? \$validator ?? []] as \$status => \$items)
            @php 
                \$items = (array)\$items;
                \$count = max(2, count(\$items)); 
            @endphp
            @for(\$i = 0; \$i < \$count; \$i++)
                @php
                    \$item = \$items[\$i] ?? [];
                    \$name = \$item['name'] ?? \$item['nama'] ?? '';
                    \$reg = \$item['reg_number'] ?? \$item['nomor_met'] ?? '';
                    \$qr = \$item['qr_code'] ?? null;
                    \$sig = \$item['signature'] ?? null;
                    \$date = \$item['date'] ?? '';
                @endphp
                <tr>
                    @if(\$i === 0)
                        <td rowspan="{{ \$count }}" style="font-weight:bold; text-transform:uppercase;">{{ \$status }}</td>
                    @endif
                    <td style="text-align:center;">{{ \$i + 1 }}</td>
                    <td>{{ \$name }}</td>
                    <td style="text-align:center;">{{ \$reg }}</td>
                    <td style="height:45px; text-align:center; vertical-align:middle;">
                        @if(\$qr)
                            <img src="{{ \$qr }}" style="width:45px;height:45px;vertical-align:middle;">
                        @elseif(\$sig)
                            <img src="{{ \$sig }}" style="max-height:40px;vertical-align:middle;">
                        @endif
                        @if(\$date)
                            <br><span style="font-size:8pt;">{{ \$date }}</span>
                        @endif
                    </td>
                </tr>
            @endfor
        @endforeach
HTML;

foreach ($bladeFiles as $filename) {
    if (str_ends_with($filename, '.blade.php') && str_starts_with($filename, 'ia')) {
        $filepath = $bladeDir . '/' . $filename;
        $content = file_get_contents($filepath);
        $original = $content;

        // Replace hardcoded PV loops
        $hardcodedPvRegex = '/@foreach\(\[\'PENYUSUN\',\'VALIDATOR\'\] as \$status\).*?@endforeach\s+@endforeach/s';
        if (preg_match($hardcodedPvRegex, $content)) {
            $content = preg_replace($hardcodedPvRegex, $dynamicTableCode, $content);
        }

        // Fix existing PV loops to show QR codes
        $pvSigRegex = '/@if\(\$p\[\'signature\'\] \?\? null\)\s*<img src="\{\{ \$p\[\'signature\'\] \}\}"[^>]*><br>\s*@endif/s';
        $content = preg_replace($pvSigRegex, "@if(\$p['qr_code'] ?? null)\n                        <img src=\"{{ \$p['qr_code'] }}\" style=\"width: 45px; height: 45px; vertical-align: middle;\"><br>\n                    @elseif(\$p['signature'] ?? null)\n                        <img src=\"{{ \$p['signature'] }}\" style=\"max-height: 35px; vertical-align: middle;\"><br>\n                    @endif", $content);

        $vvSigRegex = '/@if\(\$v\[\'signature\'\] \?\? null\)\s*<img src="\{\{ \$v\[\'signature\'\] \}\}"[^>]*><br>\s*@endif/s';
        $content = preg_replace($vvSigRegex, "@if(\$v['qr_code'] ?? null)\n                        <img src=\"{{ \$v['qr_code'] }}\" style=\"width: 45px; height: 45px; vertical-align: middle;\"><br>\n                    @elseif(\$v['signature'] ?? null)\n                        <img src=\"{{ \$v['signature'] }}\" style=\"max-height: 35px; vertical-align: middle;\"><br>\n                    @endif", $content);

        // Fix Assessor signature to prioritize QR code if it doesn't already
        // Some older templates only check $asesor_signature
        // Let's look for @if($asesor_signature ?? null) \n <img src="{{ $asesor_signature }}" ...
        $asesorSigPattern1 = '/@if\(\$asesor_signature \?\? null\)\s*<img src="\{\{ \$asesor_signature \}\}"([^>]*)>\s*<br>\s*@endif/s';
        $content = preg_replace($asesorSigPattern1, "@if(\$asesor_qr_code ?? null)\n                    <img src=\"{{ \$asesor_qr_code }}\" style=\"width: 55px; height: 55px; vertical-align: middle;\"><br>\n                @elseif(\$asesor_signature ?? null)\n                    <img src=\"{{ \$asesor_signature }}\"$1><br>\n                @endif", $content);

        // Also fix the case where there's already an @elseif($asesor_qr_code) and flip them
        $asesorSigPattern2 = '/@if\(\$asesor_signature \?\? null\)\s*<img src="\{\{ \$asesor_signature \}\}"([^>]*)>\s*<br>\s*@elseif\(\$asesor_qr_code \?\? null\)\s*<img src="\{\{ \$asesor_qr_code \}\}"([^>]*)>\s*<br>\s*@endif/s';
        $content = preg_replace($asesorSigPattern2, "@if(\$asesor_qr_code ?? null)\n                    <img src=\"{{ \$asesor_qr_code }}\"$2><br>\n                @elseif(\$asesor_signature ?? null)\n                    <img src=\"{{ \$asesor_signature }}\"$1><br>\n                @endif", $content);

        if ($content !== $original) {
            file_put_contents($filepath, $content);
            echo "Updated Blade: {$filename}\n";
        }
    }
}
echo "All tasks executed.\n";
