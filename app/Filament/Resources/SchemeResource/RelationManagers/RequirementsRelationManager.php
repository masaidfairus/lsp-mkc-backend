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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('requirement_name')
                    ->label('Nama Persyaratan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_mandatory')
                    ->label('Wajib?')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('requirement_name')
            ->columns([
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
