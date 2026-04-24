<?php

namespace App\Filament\Resources\Mapa02Resource\Pages;

use App\Filament\Resources\Mapa02Resource;
use Filament\Resources\Pages\CreateRecord;

class CreateMapa02 extends CreateRecord
{
    protected static string $resource = Mapa02Resource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return static::getModel()::updateOrCreate(
            ['registration_id' => $data['registration_id']],
            $data
        );
    }
}
