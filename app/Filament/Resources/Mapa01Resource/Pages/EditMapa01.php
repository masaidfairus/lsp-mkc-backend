<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMapa01 extends EditRecord
{
    protected static string $resource = Mapa01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
