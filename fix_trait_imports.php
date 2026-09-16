<?php
$resources = ['FrIa01', 'FrIa02', 'FrIa03', 'FrIa04a', 'FrIa04b', 'FrIa05a', 'FrIa05b', 'FrIa05c', 'FrIa06a', 'FrIa06b', 'FrIa06c', 'FrIa07', 'FrIa08', 'FrIa09', 'FrIa10', 'FrIa11'];

foreach ($resources as $res) {
    $dir = __DIR__ . "/app/Filament/Resources/{$res}Resource/Pages";
    $files = [$dir . "/Create{$res}.php", $dir . "/Edit{$res}.php"];

    foreach ($files as $file) {
        if (!file_exists($file)) continue;

        $content = file_get_contents($file);
        
        // Cek jika belum ada use trait di atas
        if (strpos($content, 'use App\Filament\Traits\HasSignatureRequestSpawner;') === false) {
            // Taruh setelah use Filament\Resources\Pages\...
            $content = preg_replace(
                '/(use Filament\\\\Resources\\\\Pages\\\\[a-zA-Z]+;)/',
                "$1\nuse App\Filament\Traits\HasSignatureRequestSpawner;",
                $content
            );
            file_put_contents($file, $content);
            echo "Fixed: $file\n";
        }
    }
}
