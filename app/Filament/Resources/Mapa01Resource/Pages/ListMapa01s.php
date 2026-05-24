<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMapa01s extends ListRecords
{
    protected static string $resource = Mapa01Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
