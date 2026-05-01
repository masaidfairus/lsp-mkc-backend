<?php

$resources = [
    'Mapa01', 'Mapa02',
    'FrIa01', 'FrIa02', 'FrIa03', 'FrIa04a', 'FrIa04b', 'FrIa05a', 'FrIa05b', 'FrIa05c', 'FrIa06a', 'FrIa06b', 'FrIa06c', 'FrIa07', 'FrIa08', 'FrIa09', 'FrIa10', 'FrIa11',
    'FrVa',
    'Ak02', 'Ak03', 'Ak05', 'Ak06'
];

$basePath = 'e:/Main/Code/lsp-mkc-backend/app/Filament/Resources';

foreach ($resources as $res) {
    $resourceDir = "{$basePath}/{$res}Resource/Pages";
    if (!is_dir($resourceDir)) continue;

    $files = glob("{$resourceDir}/*.php");
    foreach ($files as $file) {
        $filename = basename($file);
        if (!str_starts_with($filename, 'Edit') && !str_starts_with($filename, 'Create')) continue;

        $content = file_get_contents($file);
        
        // 1. Add use statement
        if (!str_contains($content, 'use App\Filament\Traits\HasFormTemplates;')) {
            $content = str_replace(
                'use Filament\Resources\Pages',
                "use App\Filament\Traits\HasFormTemplates;\nuse Filament\Resources\Pages",
                $content
            );
        }

        // 2. Use the trait
        if (!str_contains($content, 'use HasFormTemplates;')) {
            // Find the first { after class name
            $content = preg_replace(
                '/(class\s+\w+\s+extends\s+\w+\s*\{)/',
                "$1\n    use HasFormTemplates;",
                $content
            );
        }

        // 3. Add actions to getHeaderActions
        if (str_contains($content, 'protected function getHeaderActions(): array')) {
            // If getHeaderActions exists, append our actions before the return ]
            if (!str_contains($content, '$this->getSaveTemplateAction()')) {
                $content = preg_replace(
                    '/(protected\s+function\s+getHeaderActions\(\):\s+array\s*\{[^}]*return\s*\[)/s',
                    "$1\n            " . '$this->getSaveTemplateAction(),' . "\n            " . '$this->getLoadTemplateAction(),',
                    $content
                );
            }
        } else {
            // Create getHeaderActions if it doesn't exist
            $method = "\n    protected function getHeaderActions(): array\n    {\n        return [\n            " . '$this->getSaveTemplateAction(),' . "\n            " . '$this->getLoadTemplateAction(),' . "\n        ];\n    }\n";
            
            // Insert before the last }
            $pos = strrpos($content, '}');
            if ($pos !== false) {
                $content = substr_replace($content, $method, $pos, 0);
            }
        }

        file_put_to_file($file, $content);
        echo "Processed {$filename}\n";
    }
}

function file_put_to_file($file, $content) {
    file_put_contents($file, $content);
}
