<?php

namespace App\Filament\Resources\Ak05Resource\Pages;

use App\Filament\Resources\Ak05Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAk05s extends ListRecords
{
    protected static string $resource = Ak05Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
