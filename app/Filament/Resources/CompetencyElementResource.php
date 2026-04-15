<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetencyElementResource\Pages;
use App\Filament\Resources\CompetencyElementResource\RelationManagers;
use App\Models\CompetencyElement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyElementResource extends Resource
{
    protected static ?string $model = CompetencyElement::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Elemen Kompetensi';
    protected static ?string $modelLabel = 'Elemen Kompetensi';
    protected static ?string $pluralModelLabel = 'Elemen Kompetensi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Elemen Kompetensi')
                    ->schema([
                        Forms\Components\Select::make('competency_unit_id')
                            ->relationship('competencyUnit', 'unit_title')
                            ->label('Unit Kompetensi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Elemen')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('competencyUnit.unit_title')
                    ->label('Unit Kompetensi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Elemen')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('performance_criteria_count')
                    ->label('KUK')
                    ->counts('performanceCriteria')
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
            RelationManagers\PerformanceCriteriaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencyElements::route('/'),
            'create' => Pages\CreateCompetencyElement::route('/create'),
            'edit' => Pages\EditCompetencyElement::route('/{record}/edit'),
        ];
    }
}
