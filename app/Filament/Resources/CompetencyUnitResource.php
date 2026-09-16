<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetencyUnitResource\Pages;
use App\Filament\Resources\CompetencyUnitResource\RelationManagers;
use App\Models\CompetencyUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyUnitResource extends Resource
{
    protected static ?string $model = CompetencyUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Unit Kompetensi';
    protected static ?string $modelLabel = 'Unit Kompetensi';
    protected static ?string $pluralModelLabel = 'Unit Kompetensi';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Unit Kompetensi')
                    ->schema([
                        Forms\Components\Select::make('scheme_id')
                            ->relationship('scheme', 'name')
                            ->label('Skema')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('unit_code')
                            ->label('Kode Unit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit_title')
                            ->label('Judul Unit')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_title')
                    ->label('Judul Unit')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('competency_elements_count')
                    ->label('Elemen')
                    ->counts('competencyElements')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CompetencyElementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencyUnits::route('/'),
            'create' => Pages\CreateCompetencyUnit::route('/create'),
            'edit' => Pages\EditCompetencyUnit::route('/{record}/edit'),
        ];
    }
}
