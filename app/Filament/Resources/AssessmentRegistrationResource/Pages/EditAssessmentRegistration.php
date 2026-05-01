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
            Actions\Action::make('accept')
                ->label('Setujui APL.01')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function(\App\Models\AssessmentRegistration $record) {
                    $record->update(['status_apl01' => \App\Enums\Apl01Status::APPROVED->value]);
                    $this->syncAplSignatures($record);
                    \Filament\Notifications\Notification::make()->title('APL.01 Disetujui & Permintaan TTD Terkirim')->success()->send();
                })
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::APPROVED),
            Actions\Action::make('decline')
                ->label('Tolak APL.01')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn(\App\Models\AssessmentRegistration $record) => $record->update(['status_apl01' => \App\Enums\Apl01Status::REJECTED->value]))
                ->visible(fn(\App\Models\AssessmentRegistration $record) => $record->status_apl01 !== \App\Enums\Apl01Status::REJECTED),
            \Filament\Actions\Action::make('sync_signatures')
                ->label('Sinkronkan TTD Asesor')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $this->syncAplSignatures($this->record);
                    \Filament\Notifications\Notification::make()
                        ->title('Sinkronisasi Berhasil')
                        ->body('Permintaan tanda tangan untuk Asesor telah diperbarui sesuai status APL.')
                        ->success()
                        ->send();
                }),
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->syncAplSignatures($this->getRecord());
    }

    protected function syncAplSignatures(AssessmentRegistration $record): void
    {
        // 1. Bersihkan permintaan TTD Asesor yang lama jika asesor_id diganti atau dihapus
        \App\Models\SignatureRequest::where('registration_id', $record->id)
            ->where('role', 'Asesor')
            ->when($record->assessor_id, fn($q) => $q->where('user_id', '!=', $record->assessor_id))
            ->delete();

        // Jika asesor dihapus, proses berhenti di sini (sudah bersih)
        if (!$record->assessor_id) {
            return;
        }

        // 2. Sinkronkan permintaan TTD sesuai status persetujuan APL
        $docs = [
            'APL.01' => $record->status_apl01 === \App\Enums\Apl01Status::APPROVED,
            'APL.02' => $record->status_apl02 === \App\Enums\Apl02Status::APPROVED,
        ];

        foreach ($docs as $label => $shouldHaveRequest) {
            if ($shouldHaveRequest) {
                $existing = \App\Models\SignatureRequest::where([
                    'registration_id' => $record->id,
                    'user_id' => $record->assessor_id,
                    'document_label' => $label,
                ])->first();

                if (!$existing) {
                    \App\Models\SignatureRequest::create([
                        'registration_id' => $record->id,
                        'user_id' => $record->assessor_id,
                        'document_label' => $label,
                        'status' => 'pending',
                        'signable_type' => get_class($record),
                        'signable_id' => $record->id,
                        'role' => 'Asesor',
                    ]);
                }
            } else {
                // Jika status tidak APPROVED (misal di-revert ke pending), hapus request yang masih pending
                \App\Models\SignatureRequest::where([
                    'registration_id' => $record->id,
                    'user_id' => $record->assessor_id,
                    'document_label' => $label,
                    'status' => 'pending',
                ])->delete();
            }
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan perubahan'),
            $this->getCancelFormAction()->label('Kembali'),
        ];
    }
}
