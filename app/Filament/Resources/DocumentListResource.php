<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentListResource\Pages;
use App\Models\DocumentList;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use function Laravel\Prompts\title;

class DocumentListResource extends Resource
{
    protected static ?string $model = DocumentList::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Daftar Dokumen';
    protected static ?string $modelLabel = 'Daftar Dokumen';
    protected static ?string $pluralModelLabel = 'Daftar Dokumen';
    protected static ?int $navigationSort = 6;

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
                Section::make('Informasi Utama')
                    ->schema([
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('title')->required()->label('Judul Dokumen'),
                        Forms\Components\Textarea::make('description')->label('Deskripsi Dokumen'),
                    ]),

                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Detail Portofolio')
                    ->description('Hanya muncul untuk dokumen tipe Portofolio')
                    ->schema([
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('portfolioScheme.scheme_id')
                            ->label('Pilih Skema')
                            ->relationship('portfolioScheme.scheme', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\Toggle::make('portfolioScheme.is_mandatory')
                            ->label('Apakah dokumen wajib diunggah oleh Asesi?'),
                    ])
                    ->visible(fn($livewire) => $livewire->activeTab === 'portfolio'),

                // Field Khusus PERSYARATAN DASAR
                Forms\Components\Section::make('Detail Persyaratan Dasar')
                    ->description('Konfigurasi untuk bukti persyaratan dasar')
                    ->schema([
                        Forms\Components\Toggle::make('basicRequirementProof.is_mandatory')
                            ->label('Apakah dokumen wajib'),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('basicRequirementProof.or_group')
                            ->label('Kelompok Bukti Pilihan (OR Group)')
                            ->placeholder('Contoh: G1, G2, dst'),
                    ])
                    ->visible(fn($livewire) => $livewire->activeTab === 'persyaratan_dasar'),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Dokumen')
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('portfolioScheme.is_mandatory')
                    ->label('Apakah Wajib')
                    ->visible(fn($livewire) => $livewire->activeTab === 'portfolio'),

                Tables\Columns\ToggleColumn::make('basicRequirementProof.is_mandatory')
                    ->label('Apakah Wajib')
                    ->visible(fn($livewire) => $livewire->activeTab === 'persyaratan_dasar'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('portfolioScheme.scheme.name')
                    ->label('Skema')
                    ->visible(fn($livewire) => $livewire->activeTab === 'portfolio'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('basicRequirementProof.scheme.name')
                    ->label('Skema')
                    ->visible(fn($livewire) => $livewire->activeTab === 'persyaratan_dasar'),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('basicRequirementProof.or_group')
                    ->label('Kelompok Pilihan (Pilih Salah Satu)')
                    ->visible(fn($livewire) => $livewire->activeTab === 'persyaratan_dasar' || $livewire->activeTab === 'portfolio')
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
     * Mengambil eloquent query.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'administrativeProof',
            'portfolioScheme',
            'basicRequirementProof'
        ]);
    }

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentLists::route('/'),
            // 'create' => Pages\CreateDocumentList::route('/create'),
            // 'edit' => Pages\EditDocumentList::route('/{record}/edit'),
        ];
    }
}
