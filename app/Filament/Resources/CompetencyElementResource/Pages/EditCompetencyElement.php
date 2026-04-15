<?php

namespace App\Filament\Resources\CompetencyElementResource\Pages;

use App\Filament\Resources\CompetencyElementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetencyElement extends EditRecord
{
    protected static string $resource = CompetencyElementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
