<?php

namespace App\Filament\Resources\FrIa06cResource\Pages;

use App\Filament\Resources\FrIa06cResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa06c extends EditRecord
{
    protected static string $resource = FrIa06cResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
