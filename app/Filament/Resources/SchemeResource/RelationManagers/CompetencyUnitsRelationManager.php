<?php

namespace App\Filament\Resources\SchemeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'competencyUnits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('unit_code')
                    ->label('Kode Unit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_title')
                    ->label('Judul Unit')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('unit_title')
            ->columns([
                Tables\Columns\TextColumn::make('unit_code')
                    ->label('Kode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_title')
                    ->label('Judul Unit')
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
