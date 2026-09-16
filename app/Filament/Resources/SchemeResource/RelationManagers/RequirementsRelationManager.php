<?php

namespace App\Filament\Resources\SchemeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

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
                Forms\Components\TextInput::make('requirement_name')
                    ->label('Nama Persyaratan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_mandatory')
                    ->label('Wajib?')
                    ->required(),
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
            ->recordTitleAttribute('requirement_name')
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('requirement_name')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean(),
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
