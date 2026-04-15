<?php

namespace App\Filament\Resources\Apl01Resource\Pages;

use App\Filament\Resources\Apl01Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApl01 extends EditRecord
{
    protected static string $resource = Apl01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('accept')
                ->label('Diterima')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn (\App\Models\AssessmentRegistration $record) => $record->update(['status_apl01' => \App\Enums\Apl01Status::APPROVED->value]))
                ->visible(fn (\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::APPROVED),
            Actions\Action::make('decline')
                ->label('Tidak Diterima')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn (\App\Models\AssessmentRegistration $record) => $record->update(['status_apl01' => \App\Enums\Apl01Status::REJECTED->value]))
                ->visible(fn (\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::REJECTED),
            Actions\DeleteAction::make(),
        ];
    }
}
