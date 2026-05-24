<?php

namespace App\Filament\Resources\Ak03Resource\Pages;

use App\Filament\Resources\Ak03Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAk03s extends ListRecords
{
    protected static string $resource = Ak03Resource::class;

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
