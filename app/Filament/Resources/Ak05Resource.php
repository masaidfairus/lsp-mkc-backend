<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak05Resource\Pages;
use App\Models\Ak05;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak05Resource extends Resource
{
    protected static ?string $model = Ak05::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.05';
    protected static ?string $modelLabel = 'AK.05';
    protected static ?string $pluralModelLabel = 'AK.05';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([

                        // ── Registration Selector ──────────────────────────────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences   = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key               = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate         = $reg->candidate?->name ?? 'Unknown';
                                            $scheme            = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
                                        if (!$reg) return;

                                        $set('main_data.header.certification_scheme', $reg->scheme?->name ?? '');
                                        $set('main_data.header.scheme_code', $reg->scheme?->code ?? '');
                                        $set('main_data.header.tuk', $reg->tuk ?? '');

                                        if ($reg->assessor) {
                                            $set('main_data.header.assessor_name', $reg->assessor->name);
                                        }

                                        // Pre-fill first assessment result row with this asesi
                                        $set('main_data.assessment_results', [
                                            [
                                                'no'             => 1,
                                                'assessee_name'  => $reg->candidate?->name ?? '',
                                                'recommendation' => null,
                                                'description'    => '',
                                            ],
                                        ]);
                                    }),
                            ]),

                        // ── Main main_data group ───────────────────────────────
                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                // ── Header ────────────────────────────────────
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('certification_scheme')
                                                    ->label('Skema Sertifikasi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\TextInput::make('scheme_code')
                                                    ->label('Kode Skema')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('tuk')
                                                    ->label('TUK')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\TextInput::make('assessor_name')
                                                    ->label('Nama Asesor')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\DatePicker::make('assessment_date')
                                                    ->label('Tanggal Asesmen')
                                                    ->default(fn () => now()->toDateString())
                                                    ->required(),
                                            ]),
                                    ]),

                                // ── Hasil Asesmen (Daftar Asesi) ──────────────
                                Forms\Components\Section::make('Rekapitulasi Hasil Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_results')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(12)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')
                                                            ->label('No.')
                                                            ->numeric()
                                                            ->columnSpan(1),

                                                        Forms\Components\TextInput::make('assessee_name')
                                                            ->label('Nama Asesi')
                                                            ->columnSpan(5),

                                                        Forms\Components\Select::make('recommendation')
                                                            ->label('Rekomendasi')
                                                            ->options([
                                                                'K'  => 'K — Kompeten',
                                                                'BK' => 'BK — Belum Kompeten',
                                                            ])
                                                            ->native(false)
                                                            ->columnSpan(3),

                                                        Forms\Components\TextInput::make('description')
                                                            ->label('Keterangan (Kode/Judul Unit jika BK)')
                                                            ->columnSpan(3),
                                                    ]),
                                            ])
                                            ->addActionLabel('Tambah Asesi')
                                            ->reorderable(false)
                                            ->defaultItems(1),
                                    ]),

                                // ── Evaluasi Asesmen ──────────────────────────
                                Forms\Components\Section::make('Evaluasi Asesmen')
                                    ->statePath('evaluation_section')
                                    ->schema([
                                        Forms\Components\Textarea::make('positive_negative_aspects')
                                            ->label('Aspek Positif dan Negatif dalam Asesmen')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('rejection_records')
                                            ->label('Pencatatan Penolakan Hasil Asesmen')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('improvement_suggestions')
                                            ->label('Saran Perbaikan (Asesor/Personil Terkait)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // ── Footer Asesor ─────────────────────────────
                                Forms\Components\Section::make('Tanda Tangan Asesor')
                                    ->statePath('assessor_footer')
                                    ->schema([
                                        Forms\Components\Textarea::make('notes')
                                            ->label('Catatan Umum')
                                            ->rows(2)
                                            ->columnSpanFull(),

                                        Forms\Components\Group::make()
                                            ->statePath('assessor_details')
                                            ->schema([
                                                Forms\Components\Grid::make(3)
                                                    ->schema([
                                                        Forms\Components\Select::make('_signature_id')
                                                            ->label('Pilih Tanda Tangan Asesor')
                                                            ->options(UserSignature::pluck('name', 'id'))
                                                            ->reactive()
                                                            ->dehydrated(false)
                                                            ->afterStateUpdated(function ($state, callable $set) {
                                                                $sig = UserSignature::find($state);
                                                                if ($sig) {
                                                                    $set('name', $sig->name);
                                                                    $set('reg_number', $sig->reg_number ?? '');
                                                                    $set('signature', $sig->signature_image);
                                                                    $set('qr_preview', $sig->qr_code);
                                                                }
                                                            }),

                                                        Forms\Components\TextInput::make('date')
                                                            ->label('Tanggal TTD')
                                                            ->type('date')
                                                            ->default(fn () => now()->toDateString()),

                                                        Forms\Components\Placeholder::make('qr_preview')
                                                            ->label('QR Code Asesor')
                                                            ->content(fn ($get) => $get('qr_preview')
                                                                ? new HtmlString("<img src='{$get('qr_preview')}' width='100' />")
                                                                : '-'),
                                                    ]),

                                                Forms\Components\Hidden::make('name'),
                                                Forms\Components\Hidden::make('reg_number'),
                                                Forms\Components\Hidden::make('signature'),

                                                Forms\Components\Placeholder::make('_sig_preview')
                                                    ->label('Preview Tanda Tangan Asesor')
                                                    ->content(fn ($get) => $get('signature')
                                                        ? new HtmlString("<img src='{$get('signature')}' style='max-height:90px;border:1px solid #e5e7eb;border-radius:6px;padding:4px;' />")
                                                        : new HtmlString('<span class="text-sm text-gray-400 italic">Pilih tanda tangan untuk preview.</span>')),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Reg. ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(35),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAk05s::route('/'),
        ];
    }
}
