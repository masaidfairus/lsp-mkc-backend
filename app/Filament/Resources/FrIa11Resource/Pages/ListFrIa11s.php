<?php

namespace App\Filament\Resources\FrIa11Resource\Pages;

use App\Filament\Resources\FrIa11Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa11s extends ListRecords
{
    protected static string $resource = FrIa11Resource::class;

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
