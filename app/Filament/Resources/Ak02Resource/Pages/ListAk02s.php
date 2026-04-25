<?php

namespace App\Filament\Resources\Ak02Resource\Pages;

use App\Filament\Resources\Ak02Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAk02s extends ListRecords
{
    protected static string $resource = Ak02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
