<?php

namespace App\Filament\Resources\FrIa04bResource\Pages;

use App\Filament\Resources\FrIa04bResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa04b extends EditRecord
{
    protected static string $resource = FrIa04bResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
