<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\RelationManagers;

use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class EvidenceRelationManager extends RelationManager
{
    protected static string $relationship = 'filteredUserFiles';
    
    protected static ?string $title = 'Dokumen Persyaratan - APL.01';

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
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                Forms\Components\Select::make('status')
                    ->options(EvidenceStatus::class)
                    ->label('Status')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state === EvidenceStatus::Invalid->value) {
                            $set('validation_result', ValidationResult::Pending->value);
                        }
                    }),
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                Forms\Components\Select::make('validation_result')
                    ->options(ValidationResult::class)
                    ->label('Validasi')
                    ->required(),
                Forms\Components\Textarea::make('admin_notes')
                    ->label('Catatan Admin')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Mengambil eloquent query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['documentList']);
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
            ->paginated([10, 25, 50])
            ->recordTitleAttribute('documentList.title')
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('documentList.title')
                    ->label('Nama Persyaratan')
                    ->wrap()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('upload_document')
                    ->label('Berkas')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat Dokumen' : 'Tidak Ada File')
                    ->url(fn($record) => $record->upload_document ? route('private.file.view', ['record' => $record->id]) : null)
                    ->openUrlInNewTab()
                    ->color('primary'),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\IconColumn::make('validation_result')
                    ->label('Validasi')
                    ->options([
                        'heroicon-o-check-circle' => ValidationResult::MS->value,
                        'heroicon-o-x-circle' => ValidationResult::TMS->value,
                        'heroicon-o-minus-circle' => ValidationResult::Pending->value,
                    ])
                    ->colors([
                        'success' => ValidationResult::MS->value,
                        'danger' => ValidationResult::TMS->value,
                        'gray' => ValidationResult::Pending->value,
                    ]),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('admin_notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
            ])
            ->bulkActions([
                //
            ]);
    }
}
