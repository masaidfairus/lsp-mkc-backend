<?php

namespace App\Filament\Resources\FrIa05bResource\Pages;

use App\Filament\Resources\FrIa05bResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa05b extends EditRecord
{
    protected static string $resource = FrIa05bResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
