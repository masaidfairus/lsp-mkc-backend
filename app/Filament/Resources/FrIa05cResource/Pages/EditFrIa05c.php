<?php

namespace App\Filament\Resources\FrIa05cResource\Pages;

use App\Filament\Resources\FrIa05cResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class EditFrIa05c extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected function getDocumentLabel(): string
    {
        return 'FR.IA.05C';
    }
    protected static string $resource = FrIa05cResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}

