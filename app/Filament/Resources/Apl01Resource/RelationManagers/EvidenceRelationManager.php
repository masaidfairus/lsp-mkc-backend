<?php

namespace App\Filament\Resources\Apl01Resource\RelationManagers;

use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EvidenceRelationManager extends RelationManager
{
    protected static string $relationship = 'evidence'; // Belongs to AssessmentRegistration::evidence()

    protected static ?string $title = 'Dokumen Persyaratan (Evidences)';

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
            ->recordTitleAttribute('requirement.requirement_name')
            ->columns([
                Tables\Columns\TextColumn::make('requirement.requirement_name')
                    ->label('Nama Persyaratan')
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state) => $state ? 'Download / View' : 'Tidak Ada File')
                    ->url(fn ($record) => $record->file_path ? \Illuminate\Support\Facades\Storage::url($record->file_path) : null)
                    ->openUrlInNewTab()
                    ->color('primary'),
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
                    ->icon('heroicon-o-check-badge'),
            ])
            ->bulkActions([
                //
            ]);
    }
}
