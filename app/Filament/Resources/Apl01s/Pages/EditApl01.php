<?php

namespace App\Filament\Resources\Apl01s\Pages;

use App\Filament\Resources\Apl01s\Apl01Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditApl01 extends EditRecord
{
    protected static string $resource = Apl01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
