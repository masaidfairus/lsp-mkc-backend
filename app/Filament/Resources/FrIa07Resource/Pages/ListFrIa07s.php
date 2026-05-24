<?php

namespace App\Filament\Resources\FrIa07Resource\Pages;

use App\Filament\Resources\FrIa07Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa07s extends ListRecords
{
    protected static string $resource = FrIa07Resource::class;

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
