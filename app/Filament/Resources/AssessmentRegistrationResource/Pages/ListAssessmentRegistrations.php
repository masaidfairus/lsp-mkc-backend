<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\Pages;

use App\Filament\Resources\AssessmentRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssessmentRegistrations extends ListRecords
{
    protected static string $resource = AssessmentRegistrationResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            // Usually applications are created by candidates, not admins.
        ];
    }
}
