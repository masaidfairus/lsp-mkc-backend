<?php

namespace App\Filament\Resources\FrVaResource\Pages;

use App\Filament\Resources\FrVaResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;

class CreateFrVa extends CreateRecord
{
    use HasFormTemplates;
    protected static string $resource = FrVaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}
