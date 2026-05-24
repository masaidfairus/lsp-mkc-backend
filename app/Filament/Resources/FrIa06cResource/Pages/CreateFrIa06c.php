<?php

namespace App\Filament\Resources\FrIa06cResource\Pages;

use App\Filament\Resources\FrIa06cResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Traits\HasSignatureRequestSpawner;

class CreateFrIa06c extends CreateRecord
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
        return 'FR.IA.06C';
    }
    protected static string $resource = FrIa06cResource::class;

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

