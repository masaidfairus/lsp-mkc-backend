<?php

namespace App\Filament\Resources\FrIa08Resource\Pages;

use App\Filament\Resources\FrIa08Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa08 extends EditRecord
{
    protected static string $resource = FrIa08Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
