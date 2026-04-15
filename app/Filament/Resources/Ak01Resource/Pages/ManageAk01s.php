<?php

namespace App\Filament\Resources\Ak01Resource\Pages;

use App\Filament\Resources\Ak01Resource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk01s extends ManageRecords
{
    protected static string $resource = Ak01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
