<?php

namespace App\Filament\Resources\Ak02Resource\Pages;

use App\Filament\Resources\Ak02Resource;
use App\Models\Ak02;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk02s extends ManageRecords
{
    protected static string $resource = Ak02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Ak02 {
                    return Ak02::updateOrCreate(
                        ['registration_id' => $data['registration_id']],
                        $data
                    );
                }),
        ];
    }
}
