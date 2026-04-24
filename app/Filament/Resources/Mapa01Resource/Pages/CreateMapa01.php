<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMapa01 extends CreateRecord
{
    protected static string $resource = Mapa01Resource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return static::getModel()::updateOrCreate(
            ['registration_id' => $data['registration_id']],
            $data
        );
    }
}
