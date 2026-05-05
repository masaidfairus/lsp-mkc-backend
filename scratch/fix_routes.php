<?php

$file = dirname(__DIR__) . '/app/Filament/Resources/AssessmentRegistrationResource.php';
$content = file_get_contents($file);

$replacements = [
    "route('apl",
    "route('ak",
    "route('ia",
    "route('mapa",
    "route('frva"
];

foreach ($replacements as $search) {
    $replace = str_replace("route('", "\Illuminate\Support\Facades\URL::signedRoute('", $search);
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);

echo "Replaced signed routes successfully.";
