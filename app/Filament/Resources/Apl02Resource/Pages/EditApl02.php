<?php

namespace App\Filament\Resources\Apl02Resource\Pages;

use App\Filament\Resources\Apl02Resource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditApl02 extends EditRecord
{
    protected static string $resource = Apl02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Usually asesors cannot delete registrations.
        ];
    }

    protected function getFormActions(): array
    {
        return [
            

            $this->getSaveFormAction()->label('Simpan perubahan'),

            Action::make('generate')
                ->label('Cetak PDF APL-02')
                ->color('success')
                ->url(fn() => route('apl02.form', ['registration' => $this->record]))
                ->openUrlInNewTab(),

            $this->getCancelFormAction()->label('Kembali'),
        ];
    }
}
