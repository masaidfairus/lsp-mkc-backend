<?php

namespace App\Filament\Resources\Apl02Resource\Pages;

use App\Filament\Resources\Apl02Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApl02s extends ListRecords
{
    protected static string $resource = Apl02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Usually applications are created by candidates, not asesors.
        ];
    }
}
