<?php

namespace App\Filament\Resources\FrIa05aResource\Pages;

use App\Filament\Resources\FrIa05aResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa05a extends EditRecord
{
    protected static string $resource = FrIa05aResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
