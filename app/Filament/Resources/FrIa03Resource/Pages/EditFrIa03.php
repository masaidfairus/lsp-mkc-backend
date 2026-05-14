<?php

namespace App\Filament\Resources\FrIa03Resource\Pages;

use App\Filament\Resources\FrIa03Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class EditFrIa03 extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.03';
    }
    protected static string $resource = FrIa03Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}

