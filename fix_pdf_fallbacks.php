<?php
$dir = 'app/Http/Controllers/PDF';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $basename = basename($file, '.php'); // e.g. PdfAk01Controller
    
    // Determine the document name (e.g. AK.01)
    $docName = '';
    if (preg_match('/Pdf(Ak|Ia|Mapa|Apl)([0-9a-zA-Z]+)Controller/i', $basename, $matches)) {
        $docName = strtoupper($matches[1]) . '.' . strtoupper($matches[2]);
    }
    
    if (!$docName) continue;
    
    $modified = false;
    
    // Inject assessor check right after $assessor = $registration->assessor;
    $assessorTarget = "\$assessor = \$registration->assessor;";
    $assessorReplacement = "\$assessor = \$registration->assessor;\n\n        if (!\$assessor) {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show(\$registration, 'Asesor Belum Ditugaskan', 'Dokumen $docName belum dapat dicetak karena Asesor belum ditugaskan untuk pendaftaran ini.');\n        }";
    
    if (strpos($content, $assessorReplacement) === false && strpos($content, $assessorTarget) !== false) {
        $content = str_replace($assessorTarget, $assessorReplacement, $content);
        $modified = true;
    }
    
    // Look for $akxxData or $data = $registration->plannings->pluck(...)
    if (preg_match('/(\$[a-zA-Z0-9_]+)\s*=\s*\$registration->plannings->pluck\([\'"][a-zA-Z0-9_]+[\'"]\)->first\(\);/', $content, $m)) {
        $varName = $m[1]; // e.g. $ak01Data
        
        // Remove the old fallback (if (!$ak01Data) { $ak01Data = []; })
        $oldFallbackRegex = '/\/\/\s*Handle case where data is missing\s*if\s*\(\!' . preg_quote($varName) . '\)\s*\{\s*' . preg_quote($varName) . '\s*=\s*\[\];\s*\}/is';
        if (preg_match($oldFallbackRegex, $content)) {
            $newFallback = "if (!" . $varName . ") {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show(\$registration, 'Dokumen $docName Belum Siap', 'Data dokumen $docName belum diisi. Silakan lengkapi terlebih dahulu melalui menu yang sesuai.');\n        }";
            $content = preg_replace($oldFallbackRegex, $newFallback, $content);
            $modified = true;
        }
    }
    
    // Look for $instrument = $registration->assessmentInstruments()->first();
    if (strpos($content, '$instrument = $registration->assessmentInstruments()->first();') !== false) {
        $instrumentReplacement = "\$instrument = \$registration->assessmentInstruments()->first();\n\n        if (!\$instrument) {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show(\$registration, 'Dokumen $docName Belum Siap', 'Data instrumen asesmen ($docName) belum dibuat. Silakan buat instrumen terlebih dahulu.');\n        }";
        if (strpos($content, "if (!\$instrument) {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show") === false) {
            $content = str_replace('$instrument = $registration->assessmentInstruments()->first();', $instrumentReplacement, $content);
            $modified = true;
        }
    }
    
    // Look for $planning = $registration->plannings()->first();
    if (strpos($content, '$planning = $registration->plannings()->first();') !== false) {
        $planningReplacement = "\$planning = \$registration->plannings()->first();\n\n        if (!\$planning) {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show(\$registration, 'Dokumen $docName Belum Siap', 'Data perencanaan asesmen ($docName) belum dibuat.');\n        }";
        if (strpos($content, "if (!\$planning) {\n            return app(\App\Http\Controllers\PdfFallbackController::class)->show") === false) {
            $content = str_replace('$planning = $registration->plannings()->first();', $planningReplacement, $content);
            $modified = true;
        }
    }
    
    if ($modified) {
        file_put_contents($file, $content);
        echo "Updated $basename\n";
    }
}
echo "Done.\n";
