<?php

namespace App\Filament\Resources\Apl02Resource\Pages;

use App\Filament\Resources\Apl02Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApl02 extends EditRecord
{
    protected static string $resource = Apl02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Usually asesors cannot delete registrations.
        ];
    }
}
