<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SignatureInboxResource\Pages;
use App\Models\AssessmentRegistration;
use App\Models\SignatureRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SignatureInboxResource extends Resource
{
    protected static ?string $model = AssessmentRegistration::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Permintaan TTD';
    protected static ?string $navigationLabel = 'Permintaan TTD Saya';
    protected static ?string $modelLabel = 'Permintaan TTD';
    protected static ?string $pluralModelLabel = 'Permintaan TTD Saya';
    protected static ?string $slug = 'signature-inbox';

    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan pendaftaran yang memiliki permintaan tanda tangan PENDING untuk user ini
        return parent::getEloquentQuery()->whereHas('signatureRequests', function (Builder $query) {
            $query->where('user_id', auth()->id())
                ->where('status', 'pending');
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kita tidak butuh form biasa di sini karena kita akan 
                // menggunakan custom view di Edit page (atau view tab).
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('requests_count')
                    ->label('Dokumen Menunggu')
                    ->counts('signatureRequests')
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(function ($record) {
                        return $record->signatureRequests()
                            ->where('user_id', auth()->id())
                            ->where('status', 'pending')
                            ->count();
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pendaftaran')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Buka & TTD')
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignatureInboxes::route('/'),
            'edit' => Pages\ViewSignatureInbox::route('/{record}'),
        ];
    }
}
