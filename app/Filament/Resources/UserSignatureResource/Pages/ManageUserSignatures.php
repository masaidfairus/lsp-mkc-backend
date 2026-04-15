<?php

namespace App\Filament\Resources\UserSignatureResource\Pages;

use App\Filament\Resources\UserSignatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserSignatures extends ManageRecords
{
    protected static string $resource = UserSignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
