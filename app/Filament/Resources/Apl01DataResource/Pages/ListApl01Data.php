<?php

namespace App\Filament\Resources\Apl01DataResource\Pages;

use App\Filament\Resources\Apl01DataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApl01Data extends ListRecords
{
    protected static string $resource = Apl01DataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
