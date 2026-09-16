<?php

namespace App\Filament\Resources\CompetencyUnitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyElementsRelationManager extends RelationManager
{
    protected static string $relationship = 'competencyElements';

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
                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                Forms\Components\TextInput::make('title')
                    ->label('Judul Elemen')
                    ->required()
                    ->maxLength(255),
            ]);
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
            ->recordTitleAttribute('title')
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Elemen')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
