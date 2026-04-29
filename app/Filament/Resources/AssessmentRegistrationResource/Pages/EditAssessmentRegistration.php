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
            Actions\Action::make('accept_apl01')
                ->label('Terima APL-01')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status' => \App\Enums\AssessmentStatus::APL01_APPROVED]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status->isApl01Pending()),
            Actions\Action::make('decline_apl01')
                ->label('Tolak APL-01')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status' => \App\Enums\AssessmentStatus::APL01_REJECTED]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status->isApl01Pending()),
            Actions\Action::make('accept_apl02')
                ->label('Terima APL-02')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status' => \App\Enums\AssessmentStatus::APL02_APPROVED]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status === \App\Enums\AssessmentStatus::APL02_SUBMITTED),
            Actions\Action::make('decline_apl02')
                ->label('Tolak APL-02')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status' => \App\Enums\AssessmentStatus::APL02_REJECTED]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status === \App\Enums\AssessmentStatus::APL02_SUBMITTED),
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
