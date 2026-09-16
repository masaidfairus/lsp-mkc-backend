<?php

$resourcesDir = __DIR__ . '/../app/Filament/Resources';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resourcesDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filename = $file->getFilename();
        
        // Skip files that shouldn't be touched
        $skipList = [
            'UserSignatureResource.php',
            'UserResource.php',
            'SchemeResource.php',
            'CompetencyElementResource.php',
            'CompetencyUnitResource.php',
            'DocumentListResource.php',
            'PerformanceCriterionResource.php',
            'WorkGroupResource.php',
            'SignatureInboxResource.php',
            'AssessmentRegistrationResource.php',
            'MasterEducationResource.php'
        ];
        
        if (strpos($filename, 'Resource.php') !== false && !in_array($filename, $skipList)) {
            $content = file_get_contents($file->getPathname());
            
            if (strpos($content, 'wrapFormSchema') === false) {
                
                // Find public static function form(Form $form): Form
                $formMethodStart = strpos($content, 'public static function form(Form $form): Form');
                if ($formMethodStart !== false) {
                    
                    $returnSchemaStart = strpos($content, 'return $form', $formMethodStart);
                    if ($returnSchemaStart !== false) {
                        // find ->schema([
                        $schemaPos = strpos($content, '->schema([', $returnSchemaStart);
                        if ($schemaPos !== false) {
                            $replaceStart = substr_replace($content, '->schema(\App\Filament\Traits\HasStandardIaForm::wrapFormSchema([', $schemaPos, strlen('->schema(['));
                            
                            // now we must close it with ])); instead of ]); 
                            // we need to find the matching ']);' for the form method.
                            // Assuming it's at the end of the method before `    }`
                            // Find the next public static function or end of file
                            $nextFuncPos = strpos($replaceStart, 'public static function table(', $schemaPos);
                            if ($nextFuncPos === false) {
                                $nextFuncPos = strlen($replaceStart);
                            }
                            
                            $methodBody = substr($replaceStart, $schemaPos, $nextFuncPos - $schemaPos);
                            
                            // Replace the LAST `]);` in the method body
                            $lastJ = strrpos($methodBody, ']);');
                            if ($lastJ !== false) {
                                $methodBody = substr_replace($methodBody, ']));', $lastJ, strlen(']);'));
                                
                                $finalContent = substr_replace($replaceStart, $methodBody, $schemaPos, $nextFuncPos - $schemaPos);
                                
                                file_put_contents($file->getPathname(), $finalContent);
                                echo "Wrapped $filename\n";
                            }
                        }
                    }
                }
            }
        }
    }
}
