<?php

namespace App\Filament\Resources\FrVaResource\Pages;

use App\Filament\Resources\FrVaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrVas extends ListRecords
{
    protected static string $resource = FrVaResource::class;

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
