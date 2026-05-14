<?php

namespace App\Filament\Resources\FrIa08Resource\Pages;

use App\Filament\Resources\FrIa08Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa08 extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.08';
    }
    protected static string $resource = FrIa08Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}

