<?php

namespace App\Filament\Resources\Apl01Resource\Pages;

use App\Filament\Resources\Apl01Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApl01s extends ListRecords
{
    protected static string $resource = Apl01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Usually applications are created by candidates, not admins.
        ];
    }
}
