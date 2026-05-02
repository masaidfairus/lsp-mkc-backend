<?php
$files = [
    'PdfVaController.php',
    'PdfMapa02Controller.php',
    'PdfMapa01Controller.php',
    'PdfIa11Controller.php',
    'PdfIa10Controller.php',
    'PdfIa09Controller.php',
    'PdfIa08Controller.php',
    'PdfIa07Controller.php',
    'PdfIa06cController.php',
    'PdfIa06bController.php',
    'PdfIa06aController.php',
    'PdfIa05cController.php',
    'PdfIa05bController.php',
    'PdfIa05aController.php',
    'PdfIa04bController.php',
    'PdfIa04aController.php',
    'PdfIa03Controller.php',
    'PdfIA01Controller.php',
    'PdfIa02Controller.php'
];

$dir = 'app/Http/Controllers/PDF/';

foreach ($files as $file) {
    $path = $dir . $file;
    if (!file_exists($path)) continue;
    
    $content = file_get_contents($path);
    
    $old = "/\\\$user = \\\$request->user\\(\\);\\s+if \\(!in_array\\(\\\$user\\?->role \\?\\? '', \\['admin', 'asesor'\\]\\)\\) {\\s+return response\\(\\)->json\\(\\['message' => 'Forbidden.'\\], 403\\);\\s+}/";
    
    $replacement = '$user = $request->user();
        $isAuthorized = $this->isAuthorizedReviewer($user, $registration) || ($user && $user->id === $registration->user_id);
        
        if (!$isAuthorized) {
            return response()->json([\'message\' => \'Forbidden.\'], 403);
        }';
        
    $newContent = preg_replace($old, $replacement, $content);
    
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Fixed $file\n";
    } else {
        echo "Could not find pattern in $file (maybe already fixed or slightly different)\n";
    }
}
