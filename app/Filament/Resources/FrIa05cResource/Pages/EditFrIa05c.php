<?php

namespace App\Filament\Resources\FrIa05cResource\Pages;

use App\Filament\Resources\FrIa05cResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa05c extends EditRecord
{
    protected static string $resource = FrIa05cResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
