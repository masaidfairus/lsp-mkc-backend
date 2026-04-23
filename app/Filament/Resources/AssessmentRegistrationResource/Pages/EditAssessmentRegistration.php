<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\Pages;

use App\Filament\Resources\AssessmentRegistrationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAssessmentRegistration extends EditRecord
{
    protected static string $resource = AssessmentRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('accept')
                ->label('Diterima')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status_apl01' => \App\Enums\Apl01Status::APPROVED->value]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::APPROVED),
            Actions\Action::make('decline')
                ->label('Tidak Diterima')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status_apl01' => \App\Enums\Apl01Status::REJECTED->value]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::REJECTED),
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
