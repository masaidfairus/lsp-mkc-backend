<?php

namespace App\Filament\Resources\FrIa01Resource\Pages;

use App\Filament\Resources\FrIa01Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class EditFrIa01 extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    /**
     * Mengambil document label.
     *
     * @return string
     */
    protected function getDocumentLabel(): string
    {
        return 'FR.IA.01';
    }
    protected static string $resource = FrIa01Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            $this->getDeleteTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}

