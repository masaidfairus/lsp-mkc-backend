<?php

namespace App\Filament\Resources\Ak03Resource\Pages;

use App\Filament\Resources\Ak03Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAk03 extends EditRecord
{
    protected static string $resource = Ak03Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
