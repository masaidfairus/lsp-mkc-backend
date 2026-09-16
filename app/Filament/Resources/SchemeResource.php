<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchemeResource\Pages;
use App\Filament\Resources\SchemeResource\RelationManagers;
use App\Models\Scheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchemeResource extends Resource
{
    protected static ?string $model = Scheme::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Skema';
    protected static ?string $modelLabel = 'Skema';
    protected static ?string $pluralModelLabel = 'Skema';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Kode Skema')
                                    ->placeholder('e.g. SKM/01/MKC')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Skema')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('type')
                                    ->label('Jenis Skema')
                                    ->placeholder('e.g. Klaster')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Biaya Sertifikasi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('certification_fee')
                                    ->label('Biaya Sertifikasi')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->default(0.00),
                                Forms\Components\TextInput::make('recertification_fee')
                                    ->label('Biaya Sertifikasi Ulang')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->default(0.00),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Skema')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge(),
                Tables\Columns\TextColumn::make('certification_fee')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('competency_units_count')
                    ->label('Unit')
                    ->counts('competencyUnits')
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
            RelationManagers\CompetencyUnitsRelationManager::class,
            RelationManagers\RequirementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchemes::route('/'),
            'create' => Pages\CreateScheme::route('/create'),
            'edit' => Pages\EditScheme::route('/{record}/edit'),
        ];
    }
}
