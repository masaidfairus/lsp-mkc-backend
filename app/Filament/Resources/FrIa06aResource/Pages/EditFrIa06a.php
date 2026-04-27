<?php

namespace App\Filament\Resources\FrIa06aResource\Pages;

use App\Filament\Resources\FrIa06aResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa06a extends EditRecord
{
    protected static string $resource = FrIa06aResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
