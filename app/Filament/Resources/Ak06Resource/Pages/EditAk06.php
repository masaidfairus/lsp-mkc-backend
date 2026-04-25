<?php

namespace App\Filament\Resources\Ak06Resource\Pages;

use App\Filament\Resources\Ak06Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAk06 extends EditRecord
{
    protected static string $resource = Ak06Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
