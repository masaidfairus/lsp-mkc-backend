<?php

namespace App\Filament\Resources\FrIa01Resource\Pages;

use App\Filament\Resources\FrIa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa01 extends EditRecord
{
    protected static string $resource = FrIa01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
