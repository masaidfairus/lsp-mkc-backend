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
    // 1. Ini yang kamu minta
    protected static string $relationship = 'aplPortfolio';

    protected static ?string $title = 'Dokumen Portofolio - APL.02';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['fileProfile.documentList']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->recordTitleAttribute('fileProfile.documentList.title')
            ->columns([
                Tables\Columns\TextColumn::make('fileProfile.documentList.title')
                    ->label('Portofolio Skema'),

                Tables\Columns\TextColumn::make('fileProfile.upload_document')
                    ->label('Berkas')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat Dokumen' : 'Tidak Ada File')
                    ->url(fn($record) => $record->fileProfile ? route('private.file.view', $record->fileProfile->id) : null)->openUrlInNewTab()
                    ->color('primary'),
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