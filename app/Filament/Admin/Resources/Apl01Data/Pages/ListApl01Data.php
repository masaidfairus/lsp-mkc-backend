<?php

namespace App\Filament\Admin\Resources\Apl01Data\Pages;

use App\Filament\Admin\Resources\Apl01Data\Apl01DataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApl01Data extends ListRecords
{
    protected static string $resource = Apl01DataResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
