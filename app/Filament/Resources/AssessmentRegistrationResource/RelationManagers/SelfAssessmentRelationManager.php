<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\RelationManagers;

use App\Models\SelfAssessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SelfAssessmentRelationManager extends RelationManager
{
    protected static string $relationship = 'selfAssessments';
    protected static ?string $title = 'Asesmen Mandiri - APL.02';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(fn(SelfAssessment $record): string => $record->element->competencyUnit->unit_title)
                    ->description(fn(SelfAssessment $record): string => $record->element->competencyUnit->unit_code)
                    ->compact() // Membuat padding lebih ringkas
                    ->schema([
                        Forms\Components\Placeholder::make('element_title')
                            ->label(fn(SelfAssessment $record): string => $record->element->title)
                            ->content(fn($record) => new \Illuminate\Support\HtmlString("
                                <div class='p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border-l-4 border-primary-500'>
                                    <p class='text-sm font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase'>Kriteria Unjuk Kerja:</p>
                                    <ul class='list-inside list-disc space-y-1'>
                                        " . ($record?->element?->performanceCriteria?->map(function ($kuk) {
                                                            return "<li class='text-base font-semibold text-gray-900 dark:text-gray-100'>
                                                        {$kuk->name}
                                                    </li>";
                                                        })->implode('') ?? "<li class='text-gray-400'>Data tidak ditemukan</li>") . "
                                    </ul>
                                </div>
                            "))
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\ToggleButtons::make('is_competent')
                                    ->label('Apakah Kompeten')
                                    ->boolean()
                                    ->grouped() 
                                    ->icons([
                                        true => 'heroicon-o-check-circle',
                                        false => 'heroicon-o-x-circle',
                                    ])
                                    ->colors([
                                        true => 'success',
                                        false => 'danger',
                                    ])
                                    ->required(),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with([
            'element.competencyUnit',
            'element.performanceCriteria'
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->recordTitleAttribute('is_competent')
            ->columns([
                Tables\Columns\TextColumn::make('element.competencyUnit.unit_title')
                    ->label('Unit Kompetensi')
                    ->description(fn(SelfAssessment $record): string => $record->element->competencyUnit->unit_code)
                    ->wrap(),
                Tables\Columns\TextColumn::make('element.title')
                    ->label('Elemen'),
                Tables\Columns\IconColumn::make('is_competent')
                    ->label('Kompeten')
                    ->options([
                        'heroicon-o-check-circle' => true,
                        'heroicon-o-x-circle' => false,
                    ])
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),
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
                    ->modalHeading('Mengubah Asesmen Mandiri')
                    ->modalSubmitActionLabel('Simpan Asesmen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
