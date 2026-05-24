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
    protected static ?string $globalSearchEloquentKey = null;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Permintaan TTD';
    protected static ?string $navigationLabel = 'Permintaan TTD Saya';
    protected static ?string $modelLabel = 'Permintaan TTD';
    protected static ?string $pluralModelLabel = 'Permintaan TTD Saya';
    protected static ?string $slug = 'signature-inbox';

    /**
     * Can view any.
     *
     * @return bool
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_signature::inbox');
    }

    /**
     * Mengambil eloquent query.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan pendaftaran yang memiliki permintaan tanda tangan PENDING untuk user ini
        // Gunakan withCount agar penghitungan dilakukan dalam satu query (efisien)
        // Gunakan with untuk eager loading relasi asesi dan skema
        return parent::getEloquentQuery()
            ->with(['candidate', 'scheme'])
            ->withCount(['signatureRequests as pending_requests_count' => function (Builder $query) {
                $query->where('user_id', auth()->id())
                    ->where('status', 'pending');
            }])
            ->whereHas('signatureRequests', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
    }

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
                // Kita tidak butuh form biasa di sini karena kita akan 
                // menggunakan custom view di Edit page (atau view tab).
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
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('signatureRequests', function (Builder $query) {
                $query->where('user_id', auth()->id())
                    ->where('status', 'pending');
            }))
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('pending_requests_count')
                    ->label('Dokumen Menunggu')
                    ->badge()
                    ->color('warning'),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
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

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignatureInboxes::route('/'),
            'edit' => Pages\ViewSignatureInbox::route('/{record}'),
        ];
    }
}
