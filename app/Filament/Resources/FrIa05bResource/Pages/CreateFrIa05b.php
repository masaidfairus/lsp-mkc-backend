<?php

namespace App\Filament\Resources\FrIa05bResource\Pages;

use App\Filament\Resources\FrIa05bResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa05b extends CreateRecord
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
        return 'FR.IA.05B';
    }
    protected static string $resource = FrIa05bResource::class;

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
        ];
    }
}

