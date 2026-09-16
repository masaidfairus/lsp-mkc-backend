<?php

namespace App\Filament\Resources\Mapa02Resource\Pages;

use App\Filament\Resources\Mapa02Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMapa02s extends ListRecords
{
    protected static string $resource = Mapa02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
