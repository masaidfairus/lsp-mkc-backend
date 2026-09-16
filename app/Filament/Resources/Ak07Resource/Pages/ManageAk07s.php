<?php

namespace App\Filament\Resources\Ak07Resource\Pages;

use App\Filament\Resources\Ak07Resource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk07s extends ManageRecords
{
    protected static string $resource = Ak07Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): \App\Models\Ak07 {
                    return \App\Models\Ak07::updateOrCreate(
                        ['registration_id' => $data['registration_id']],
                        $data
                    );
                }),
        ];
    }
}
