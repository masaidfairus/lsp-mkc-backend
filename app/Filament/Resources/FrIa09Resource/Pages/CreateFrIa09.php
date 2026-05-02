<?php

namespace App\Filament\Resources\FrIa09Resource\Pages;

use App\Filament\Resources\FrIa09Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa09 extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.09';
    }
    protected static string $resource = FrIa09Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}

