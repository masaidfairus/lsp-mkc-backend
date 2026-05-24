<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerformanceCriterionResource\Pages;
use App\Filament\Resources\PerformanceCriterionResource\RelationManagers;
use App\Models\PerformanceCriterion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerformanceCriterionResource extends Resource
{
    protected static ?string $model = PerformanceCriterion::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Kriteria Unjuk Kerja';
    protected static ?string $modelLabel = 'Kriteria Unjuk Kerja';
    protected static ?string $pluralModelLabel = 'Kriteria Unjuk Kerja';
    protected static ?int $navigationSort = 4;

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
                Forms\Components\Section::make('Data Kriteria Unjuk Kerja (KUK)')
                    ->schema([
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('element_id')
                            ->relationship('element', 'title')
                            ->label('Elemen Kompetensi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi KUK')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('element.title')
                    ->label('Elemen')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi KUK')
                    ->searchable()
                    ->wrap(),
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
            //
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
            'index' => Pages\ListPerformanceCriteria::route('/'),
            'create' => Pages\CreatePerformanceCriterion::route('/create'),
            'edit' => Pages\EditPerformanceCriterion::route('/{record}/edit'),
        ];
    }
}
