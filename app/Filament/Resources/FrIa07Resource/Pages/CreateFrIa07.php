<?php

namespace App\Filament\Resources\FrIa07Resource\Pages;

use App\Filament\Resources\FrIa07Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa07 extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.07';
    }
    protected static string $resource = FrIa07Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}

