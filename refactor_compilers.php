<?php
$dir = __DIR__ . '/app/Http/Controllers/PDF';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $changed = false;

    // We want to find where compilers are assigned, e.g., $compilers = $docControl['compilers'] ?? [];
    // and inject a loop to fetch their signatures.
    // However, the variable names vary. Sometimes it's $data['penyusun'] = $docControl['compilers']...
    // Let's use a simpler approach: we can just add a helper method in the Controller base class or the trait,
    // OR we can just add a block of code right before `$pdf = Pdf::loadView`
    
    // Pattern to find: $pdf = Pdf::loadView(
    $injection = <<<'PHP'
        // --- Inject Signatures for Compilers & Validators ---
        if (isset($data['compilers']) && is_array($data['compilers'])) {
            foreach ($data['compilers'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['penyusun']) && is_array($data['penyusun'])) {
            foreach ($data['penyusun'] as &$c) {
                $sig = $this->getUserSignature($c['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Penyusun');
                $c['signature'] = $sig['signature'];
                $c['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validators']) && is_array($data['validators'])) {
            foreach ($data['validators'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        if (isset($data['validator']) && is_array($data['validator'])) {
            foreach ($data['validator'] as &$v) {
                $sig = $this->getUserSignature($v['user_id'] ?? null, $instrument?->id ?? ($record->id ?? null), 'Validator');
                $v['signature'] = $sig['signature'];
                $v['qr_code']   = $sig['qr_code'];
            }
        }
        // ----------------------------------------------------
PHP;

    if (strpos($content, '// --- Inject Signatures for Compilers & Validators ---') === false && strpos($content, '$pdf = Pdf::loadView') !== false) {
        $content = str_replace('$pdf = Pdf::loadView', $injection . "\n\n        \$pdf = Pdf::loadView", $content);
        $changed = true;
    }

    if ($changed) {
        file_put_contents($file, $content);
        echo "Injected compilers signatures in " . basename($file) . "\n";
    }
}
