<?php

namespace App\Filament\Resources\FrIa07Resource\Pages;

use App\Filament\Resources\FrIa07Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa07 extends EditRecord
{
    protected static string $resource = FrIa07Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
