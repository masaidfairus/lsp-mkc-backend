<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\Pages;

use App\Filament\Resources\AssessmentRegistrationResource;
use App\Models\AssessmentRegistration;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAssessmentRegistration extends EditRecord
{
    protected static string $resource = AssessmentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan perubahan'),
            $this->getCancelFormAction()->label('Kembali'),
        ];
    }
}
