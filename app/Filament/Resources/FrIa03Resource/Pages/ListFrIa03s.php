<?php

namespace App\Filament\Resources\FrIa03Resource\Pages;

use App\Filament\Resources\FrIa03Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa03s extends ListRecords
{
    protected static string $resource = FrIa03Resource::class;

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
