<?php

namespace App\Filament\Resources\FrIa09Resource\Pages;

use App\Filament\Resources\FrIa09Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrIa09 extends EditRecord
{
    protected static string $resource = FrIa09Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
