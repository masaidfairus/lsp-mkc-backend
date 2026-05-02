<?php

namespace App\Filament\Resources\FrIa11Resource\Pages;

use App\Filament\Resources\FrIa11Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa11 extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.11';
    }
    protected static string $resource = FrIa11Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}

