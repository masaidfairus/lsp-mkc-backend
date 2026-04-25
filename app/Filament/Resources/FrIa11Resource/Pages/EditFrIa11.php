<?php

namespace App\Filament\Resources\FrIa11Resource\Pages;

use App\Filament\Resources\FrIa11Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa11 extends EditRecord
{
    protected static string $resource = FrIa11Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
