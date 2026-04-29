<?php

$dir = 'app/Filament/Resources';
$files = glob($dir . '/FrIa*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;

    // 1. Update afterStateHydrated closures
    $content = preg_replace(
        '/afterStateHydrated\(function\s*\(\$state,\s*callable\s*\$set\)\s*\{/',
        'afterStateHydrated(function ($state, callable $set, callable $get) {',
        $content
    );

    // 2. Update afterStateUpdated closures
    $content = preg_replace(
        '/afterStateUpdated\(function\s*\(\$state,\s*callable\s*\$set\)\s*\{/',
        'afterStateUpdated(function ($state, callable $set, callable $get) {',
        $content
    );

    // 3. Update method calls inside the closures
    $content = preg_replace(
        '/(self::populateIa[a-zA-Z0-9]+Data\(\$state,\s*\$set)\);/',
        '$1, $get);',
        $content
    );

    // 4. Update method signatures
    $content = preg_replace(
        '/protected\s+static\s+function\s+(populateIa[a-zA-Z0-9]+Data)\(\$state,\s*callable\s*\$set\)/',
        'protected static function $1($state, callable $set, callable $get = null)',
        $content
    );

    // 5. Replace bad $set('...', null) usages with $get(...)
    // E.g., if (!$set('main_data.header.assessment_date', null)) { -> if ($get && !$get('main_data.header.assessment_date')) {
    // E.g., $existingTasks = (array) $set('main_data.demonstration_tasks', null); -> $existingTasks = $get ? (array) $get('main_data.demonstration_tasks') : [];
    
    // First, for the if (!$set(..., null)) { ... } pattern
    // Usually it's: if (!$set('something', null)) { $set('something', now()...) }
    // Which actually meant to check if it's empty!
    $content = preg_replace(
        '/if\s*\(!\$set\(([\'"][^\'"]+[\'"]),\s*null\)\)/',
        'if ($get && !$get($1))',
        $content
    );

    // Second, for variable assignments: $var = (array) $set('...', null);
    $content = preg_replace(
        '/(\$\w+)\s*=\s*\(array\)\s*\$set\(([\'"][^\'"]+[\'"]),\s*null\);/',
        '$1 = $get ? (array) $get($2) : [];',
        $content
    );

    // Third, for direct assignments: $var = $set('...', null);
    $content = preg_replace(
        '/(\$\w+)\s*=\s*\$set\(([\'"][^\'"]+[\'"]),\s*null\);/',
        '$1 = $get ? $get($2) : null;',
        $content
    );


    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: " . basename($file) . PHP_EOL;
    }
}
echo "Done\n";
