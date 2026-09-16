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

    /**
     * Form.
     *
     * @param  Form  $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Data Unit Kompetensi')
                    ->schema([
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('scheme_id')
                            ->relationship('scheme', 'name')
                            ->label('Skema')
                            ->searchable()
                            ->preload()
                            ->required(),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('unit_code')
                            ->label('Kode Unit')
                            ->required()
                            ->maxLength(255),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('unit_title')
                            ->label('Judul Unit')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('unit_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('unit_title')
                    ->label('Judul Unit')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
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

    /**
     * Mendapatkan relasi getRelations.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\CompetencyElementsRelationManager::class,
        ];
    }

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencyUnits::route('/'),
            'create' => Pages\CreateCompetencyUnit::route('/create'),
            'edit' => Pages\EditCompetencyUnit::route('/{record}/edit'),
        ];
    }
}
