<?php
$dir = __DIR__ . '/app/Http/Controllers/PDF';
$files = glob($dir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Replace definition: private function getUserSignature($userId) 
    // This regex looks for `private function getUserSignature($userId)` and its body
    $content = preg_replace(
        '/private function getUserSignature\(\$userId\)\s*\{.*?\n    \}/s',
        'private function getUserSignature($userId, $modelId = null, $role = null)
    {
        if (!$userId) return [\'signature\' => null, \'qr_code\' => null];

        $query = \App\Models\SignatureRequest::where(\'user_id\', $userId)->where(\'status\', \'signed\');
        if ($modelId) $query->where(\'signable_id\', $modelId);
        if ($role) $query->where(\'role\', $role);
        
        $req = $query->latest(\'signed_at\')->first();
        if ($req) {
            return [\'signature\' => $req->raw_signature, \'qr_code\' => $req->qr_code];
        }

        $sig = \Illuminate\Support\Facades\DB::table(\'user_signatures\')->where(\'user_id\', $userId)->latest(\'created_at\')->first();
        return $sig ? [\'signature\' => $sig->signature_image ?? $sig->signature, \'qr_code\' => $sig->qr_code] : [\'signature\' => null, \'qr_code\' => null];
    }',
        $content
    );

    // Some files might use static private function
    $content = preg_replace(
        '/private static function getUserSignature\(\$userId\)\s*\{.*?\n    \}/s',
        'private static function getUserSignature($userId, $modelId = null, $role = null)
    {
        if (!$userId) return [\'signature\' => null, \'qr_code\' => null];

        $query = \App\Models\SignatureRequest::where(\'user_id\', $userId)->where(\'status\', \'signed\');
        if ($modelId) $query->where(\'signable_id\', $modelId);
        if ($role) $query->where(\'role\', $role);
        
        $req = $query->latest(\'signed_at\')->first();
        if ($req) {
            return [\'signature\' => $req->raw_signature, \'qr_code\' => $req->qr_code];
        }

        $sig = \Illuminate\Support\Facades\DB::table(\'user_signatures\')->where(\'user_id\', $userId)->latest(\'created_at\')->first();
        return $sig ? [\'signature\' => $sig->signature_image ?? $sig->signature, \'qr_code\' => $sig->qr_code] : [\'signature\' => null, \'qr_code\' => null];
    }',
        $content
    );

    // Calls: $this->getUserSignature($registration->assessor?->id ?? null);
    $content = preg_replace(
        '/\$this->getUserSignature\(\$registration->assessor\?->id \?\? null\)/',
        '$this->getUserSignature($registration->assessor?->id ?? null, $instrument?->id ?? ($record->id ?? null), \'Asesor\')',
        $content
    );
    
    // MAPA uses static::getUserSignature and passes $userId. We can catch that if needed, 
    // but the fallback handles old logic. For explicit roles in other controllers:
    $content = preg_replace(
        '/\$this->getUserSignature\(\$penyusunId\)/',
        '$this->getUserSignature($penyusunId, $instrument?->id ?? ($record->id ?? null), \'Penyusun\')',
        $content
    );
    $content = preg_replace(
        '/\$this->getUserSignature\(\$validatorId\)/',
        '$this->getUserSignature($validatorId, $instrument?->id ?? ($record->id ?? null), \'Validator\')',
        $content
    );

    file_put_contents($file, $content);
    echo "Updated " . basename($file) . "\n";
}
