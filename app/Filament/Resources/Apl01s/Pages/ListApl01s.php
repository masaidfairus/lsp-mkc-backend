<?php

namespace App\Filament\Resources\Apl01s\Pages;

use App\Filament\Resources\Apl01s\Apl01Resource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApl01s extends ListRecords
{
    protected static string $resource = Apl01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
