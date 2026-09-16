<?php

namespace App\Filament\Resources\FrIa09Resource\Pages;

use App\Filament\Resources\FrIa09Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa09s extends ListRecords
{
    protected static string $resource = FrIa09Resource::class;

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
