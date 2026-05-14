<?php

$dir = 'app/Filament/Resources';
$files = glob($dir . '/FrIa*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;

    // We want to pass true for afterStateUpdated
    // Look for: afterStateUpdated(function ($state, callable $set, callable $get) {
    //     if ($state) {
    //         self::populateIa...Data($state, $set, $get);
    //     }
    // })
    // Change to: self::populateIa...Data($state, $set, $get, true);
    
    // Using regex to carefully replace just the call inside afterStateUpdated
    $content = preg_replace_callback(
        '/afterStateUpdated\(function\s*\(\$state,\s*callable\s*\$set,\s*callable\s*\$get\)\s*\{\s*if\s*\(\$state\)\s*\{\s*self::populateIa[a-zA-Z0-9]+Data\(\$state,\s*\$set,\s*\$get\);\s*\}\s*\}\)/',
        function ($matches) {
            return str_replace('$get);', '$get, true);', $matches[0]);
        },
        $content
    );

    // Update the method signature to accept $isUpdate
    $content = preg_replace(
        '/protected\s+static\s+function\s+(populateIa[a-zA-Z0-9]+Data)\(\$state,\s*callable\s*\$set,\s*callable\s*\$get\s*=\s*null\)/',
        'protected static function $1($state, callable $set, callable $get = null, bool $isUpdate = false)',
        $content
    );

    // Now update the empty() check to also check if $isUpdate is true
    // E.g., if (empty($existingTasks) && $reg->scheme) -> if (($isUpdate || empty($existingTasks)) && $reg->scheme)
    // There are various patterns for this:
    // if (empty($existingGroups) && $reg->scheme)
    // if (empty($existingSections) && $reg->scheme)
    // if (empty($existingReviews) && $reg->scheme)
    // We can just find: if (empty($existing[A-Za-z0-9]+) &&
    $content = preg_replace(
        '/if\s*\(\s*empty\(\$(\w+)\)\s*&&/',
        'if (($isUpdate || empty($$1) || empty(array_filter((array)$$1))) &&',
        $content
    );
    
    // Some forms don't check for scheme, e.g. FrIa04b has: if (empty($existingQuestions)) {
    // Let's do a more general replace for the empty check if it follows $existing...
    $content = preg_replace(
        '/if\s*\(\s*empty\(\$(\w+)\)\s*\)/',
        'if ($isUpdate || empty($$1) || empty(array_filter((array)$$1)))',
        $content
    );


    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: " . basename($file) . PHP_EOL;
    }
}
echo "Done\n";
