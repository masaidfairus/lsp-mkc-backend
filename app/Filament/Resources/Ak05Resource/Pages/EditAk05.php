<?php

namespace App\Filament\Resources\Ak05Resource\Pages;

use App\Filament\Resources\Ak05Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAk05 extends EditRecord
{
    protected static string $resource = Ak05Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
