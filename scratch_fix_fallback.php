<?php
$bladeDir = 'resources/views/forms/IA';
$files = glob($bladeDir . '/ia*.blade.php');
foreach ($files as $filepath) {
    $content = file_get_contents($filepath);
    $original = $content;
    
    // Check if it has @if($asesor_qr_code) without @elseif($asesor_signature)
    $pattern = '/@if\(\$asesor_qr_code\)\s*<img src="\{\{ \$asesor_qr_code \}\}"[^>]*>\s*@endif/s';
    $content = preg_replace($pattern, "@if(\$asesor_qr_code)\n                                <img src=\"{{ \$asesor_qr_code }}\" style=\"width: 50px; height: 50px; vertical-align: middle;\">\n                            @elseif(\$asesor_signature ?? null)\n                                <img src=\"{{ \$asesor_signature }}\" style=\"max-height: 50px; vertical-align: middle;\">\n                            @endif", $content);

    if ($content !== $original) {
        file_put_contents($filepath, $content);
        echo 'Updated fallback in ' . basename($filepath) . PHP_EOL;
    }
}
echo "Done\n";
