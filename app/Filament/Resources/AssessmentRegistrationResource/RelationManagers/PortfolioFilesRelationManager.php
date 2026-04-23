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

class PortfolioFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'portfolioFiles';

    protected static ?string $title = 'Dokumen Portofolio - APL.02';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\Select::make('validation_result')
                    ->options(ValidationResult::class)
                    ->label('Validasi')
                    ->required(),
                Forms\Components\Textarea::make('admin_notes')
                    ->label('Catatan Admin')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('documentList.title')
            ->columns([
                Tables\Columns\TextColumn::make('documentList.title')->label('Portofolio Skema'),
                Tables\Columns\TextColumn::make('upload_document')
                    ->label('Berkas')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat Dokumen' : 'Tidak Ada File')
                    ->url(fn($record) => $record->upload_document ? Storage::url($record->upload_document) : null)
                    ->openUrlInNewTab()
                    ->color('primary'),
                // Tables\Columns\TextColumn::make('status')
                //     ->label('Status')
                //     ->badge(),
                // Tables\Columns\IconColumn::make('validation_result')
                //     ->label('Validasi')
                //     ->options([
                //         'heroicon-o-check-circle' => ValidationResult::MS->value,
                //         'heroicon-o-x-circle' => ValidationResult::TMS->value,
                //         'heroicon-o-minus-circle' => ValidationResult::Pending->value,
                //     ])
                //     ->colors([
                //         'success' => ValidationResult::MS->value,
                //         'danger' => ValidationResult::TMS->value,
                //         'gray' => ValidationResult::Pending->value,
                //     ]),
                // Tables\Columns\TextColumn::make('admin_notes')
                //     ->label('Catatan')
                //     ->limit(30)
                //     ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
