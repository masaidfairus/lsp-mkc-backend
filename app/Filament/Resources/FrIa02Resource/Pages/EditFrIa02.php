<?php

namespace App\Filament\Resources\FrIa02Resource\Pages;

use App\Filament\Resources\FrIa02Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa02 extends EditRecord
{
    protected static string $resource = FrIa02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
