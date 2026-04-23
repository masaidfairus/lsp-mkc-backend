<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\RelationManagers;

use App\Models\Apl01Data;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AplDataRelationManager extends RelationManager
{
    protected static string $relationship = 'aplData';
    protected static ?string $title = 'Profil Asesi';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin'),

                Tables\Columns\TextColumn::make('place_of_birth')
                    ->label('Tempat Lahir'),

                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date('d F Y'),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No. Telp'),

                Tables\Columns\TextColumn::make('education_qualifications')
                    ->label('Pendidikan'),

                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->description(fn(Apl01Data $record): string => $record->job_title ?? ''),
            ])
            ->actions([
            ]);
    }
}
