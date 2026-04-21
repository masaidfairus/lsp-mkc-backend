<?php

namespace App\Filament\Resources\Ak07Resource\Pages;

use App\Filament\Resources\Ak07Resource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk07s extends ManageRecords
{
    protected static string $resource = Ak07Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
