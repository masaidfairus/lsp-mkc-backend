<?php

namespace App\Filament\Resources\FrIa04bResource\Pages;

use App\Filament\Resources\FrIa04bResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa04b extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.04B';
    }
    protected static string $resource = FrIa04bResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}

