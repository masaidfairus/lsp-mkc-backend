<?php

namespace App\Filament\Resources\FrVaResource\Pages;

use App\Filament\Resources\FrVaResource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;

class EditFrVa extends EditRecord
{
    use HasFormTemplates;
    protected static string $resource = FrVaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
