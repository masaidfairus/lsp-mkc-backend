<?php

namespace App\Filament\Resources\FrVaResource\Pages;

use App\Filament\Resources\FrVaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrVa extends EditRecord
{
    protected static string $resource = FrVaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
