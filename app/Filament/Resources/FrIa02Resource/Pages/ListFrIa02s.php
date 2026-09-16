<?php

namespace App\Filament\Resources\FrIa02Resource\Pages;

use App\Filament\Resources\FrIa02Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa02s extends ListRecords
{
    protected static string $resource = FrIa02Resource::class;

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
