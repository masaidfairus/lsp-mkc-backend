<?php

namespace App\Filament\Resources\FrIa04aResource\Pages;

use App\Filament\Resources\FrIa04aResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa04a extends EditRecord
{
    protected static string $resource = FrIa04aResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
