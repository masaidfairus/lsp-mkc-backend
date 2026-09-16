<?php

namespace App\Filament\Resources;

use App\Enums\AppealStatus;
use App\Filament\Resources\Ak04Resource\Pages;
use App\Models\Appeal;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak04Resource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.04';
    protected static ?string $modelLabel = 'AK.04';
    protected static ?string $pluralModelLabel = 'AK.04';
    protected static ?int $navigationSort = 4;

    /**
     * Can edit.
     *
     * @param  mixed  $record
     * @return bool
     */
    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole(['admin', 'superadmin']);
    }

    /**
     * Can delete.
     *
     * @param  mixed  $record
     * @return bool
     */
    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole(['admin', 'superadmin']);
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
                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Status Verifikasi')
                    ->icon('heroicon-m-check-badge')
                    ->schema([
                        Forms\Components\Grid::make(12)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Pendaftaran Terkait')
                                    ->relationship('registration', 'id')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $candidate = $record->candidate?->name ?? 'Unknown';
                                        return "{$candidate} (#{$record->id})";
                                    })
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(4),
                                
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('status')
                                    ->label('Status Verifikasi')
                                    ->options(AppealStatus::class)
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(4),
                                
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Waktu Pengajuan')
                                    ->content(fn ($record) => $record?->created_at?->translatedFormat('d F Y H:i') ?? '-')
                                    ->columnSpan(4),
                            ]),
                    ]),

                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Detail Banding (AK.04)')
                    ->schema([
                        Forms\Components\Grid::make(12)
                            ->schema([
                                Forms\Components\Placeholder::make('asesi_name')
                                    ->label('Nama Asesi')
                                    ->content(fn ($record) => $record?->ak04_data['asesi_name'] ?? '-')
                                    ->columnSpan(6),
                                Forms\Components\Placeholder::make('asesor_name')
                                    ->label('Nama Asesor')
                                    ->content(fn ($record) => $record?->ak04_data['asesor_name'] ?? '-')
                                    ->columnSpan(6),
                                Forms\Components\Placeholder::make('assessment_date')
                                    ->label('Tanggal Asesmen')
                                    ->content(fn ($record) => $record?->ak04_data['assessment_date'] ?? '-')
                                    ->columnSpan(6),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Placeholder::make('scheme_name')
                                            ->label('Skema Sertifikasi')
                                            ->content(fn ($record) => $record?->ak04_data['appeal_target']['scheme_name'] ?? '-'),
                                        Forms\Components\Placeholder::make('scheme_code')
                                            ->label('Kode Skema')
                                            ->content(fn ($record) => $record?->ak04_data['appeal_target']['scheme_code'] ?? '-'),
                                    ])->columnSpan(6),
                            ]),
                        
                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                        Forms\Components\Section::make('Pertanyaan Banding')
                            ->schema([
                                Forms\Components\Placeholder::make('appeal_questions_view')
                                    ->label('')
                                    ->content(function ($record) {
                                        $questions = $record?->ak04_data['appeal_questions'] ?? [];
                                        if (empty($questions)) return 'Tidak ada data pertanyaan.';
                                        
                                        $html = '<div class="space-y-3">';
                                        foreach ($questions as $q) {
                                            $answer = ($q['answer'] ?? false) ? 'YA' : 'TIDAK';
                                            $isYa = ($q['answer'] ?? false);
                                            
                                            $itemBg = $isYa ? 'bg-emerald-50/50 dark:bg-emerald-500/5' : 'bg-rose-50/50 dark:bg-rose-500/5';
                                            $itemBorder = $isYa ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-rose-200 dark:border-rose-500/20';
                                            
                                            $labelColor = $isYa ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400';
                                            $badgeBg = $isYa ? 'bg-emerald-600 dark:bg-emerald-500' : 'bg-rose-600 dark:bg-rose-500';
                                            
                                            $html .= "
                                                <div class='flex justify-between items-center p-4 {$itemBg} rounded-xl border {$itemBorder} shadow-sm'>
                                                    <div class='text-sm font-medium text-gray-800 dark:text-gray-200 pr-4'>{$q['label']}</div>
                                                    <div class='flex-shrink-0 px-4 py-1.5 rounded-lg {$badgeBg} text-white text-[10px] font-black tracking-[0.2em] shadow-lg'>
                                                        {$answer}
                                                    </div>
                                                </div>";
                                        }
                                        $html .= '</div>';
                                        return new HtmlString($html);
                                    }),
                            ]),

                        Forms\Components\Placeholder::make('reason_notes')
                            ->label('Alasan Banding')
                            ->content(fn ($record) => $record?->ak04_data['reason_notes'] ?? '-')
                            ->columnSpanFull(),

                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                        Forms\Components\Section::make('Tanda Tangan Asesi')
                            ->schema([
                                Forms\Components\Placeholder::make('signature_preview')
                                    ->label('Verifikasi Digital (QR Code)')
                                    ->content(function ($record) {
                                        if (!$record) return '-';
                                        
                                        $qrCode = $record->asesi_qr_code;
                                        
                                        if (!$qrCode) {
                                            return new HtmlString("<div class='p-4 bg-gray-50 border border-dashed rounded-lg text-gray-500 text-xs'>
                                                Belum ada tanda tangan digital yang terverifikasi (QR Code tidak ditemukan).
                                            </div>");
                                        }
                                        
                                        return new HtmlString("
                                            <div class='flex flex-col gap-2'>
                                                <img src='{$qrCode}' style='max-height:150px; width:150px; border:1px solid #e5e7eb; border-radius:8px;' />
                                                <span class='text-[10px] text-gray-400'>Terverifikasi secara digital</span>
                                            </div>
                                        ");
                                    }),
                                Forms\Components\Placeholder::make('signature_date')
                                    ->label('Tanggal Tanda Tangan')
                                    ->content(fn ($record) => $record?->ak04_data['signature']['date'] ?? '-'),
                            ])->columns(2),
                    ])->collapsible(),
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
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('ID Daftar')
                    ->sortable()
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.candidate.name')
                    ->label('Asesi')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(30),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (AppealStatus $state): string => match ($state) {
                        AppealStatus::PENDING => 'warning',
                        AppealStatus::APPROVED => 'success',
                        AppealStatus::REJECTED => 'danger',
                    })
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(AppealStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make()
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ManageAk04s::route('/'),
        ];
    }
}
