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


    /**
     * Form.
     *
     * @param  Form  $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ])
            ->columns(2);
    }

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('place_of_birth')
                    ->label('Tempat Lahir'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date('d F Y'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No. Telp'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('education_qualifications')
                    ->label('Pendidikan'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->description(fn(Apl01Data $record): string => $record->job_title ?? ''),
            ])
            ->actions([
            ]);
    }
}
