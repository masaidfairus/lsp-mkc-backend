<?php

namespace App\Filament\Resources\FrIa11Resource\Pages;

use App\Filament\Resources\FrIa11Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class EditFrIa11 extends EditRecord
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
            Actions\DeleteAction::make(),
        ];
    }
}

