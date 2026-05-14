<?php

namespace App\Filament\Resources\FrIa05bResource\Pages;

use App\Filament\Resources\FrIa05bResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class EditFrIa05b extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.05B';
    }
    protected static string $resource = FrIa05bResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}

