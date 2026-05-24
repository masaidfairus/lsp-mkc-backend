<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak03Resource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\Ak03;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class Ak03Resource extends Resource
{
    use HasStandardIaForm;
    protected static ?string $model = Ak03::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.03';
    protected static ?string $modelLabel = 'AK.03';
    protected static ?string $pluralModelLabel = 'AK.03';
    protected static ?int $navigationSort = 3;

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
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([

                        // ── Registration Selector ──────────────────────────────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
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
                                        $set('main_data.header.assessee_name', $reg->candidate?->name ?? '');
                                        $set('main_data.header.tuk', $reg->tuk ?? '');

                                        if ($reg->assessor) {
                                            $set('main_data.header.assessor_name', $reg->assessor->name);
                                            
                                            // Auto-populate assessor signature info
                                            self::populateAssessorSignature($reg->assessor_id, $set, 'signatures.assessor');
                                        }
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
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('certification_scheme')
                                                    ->label('Skema Sertifikasi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('scheme_code')
                                                    ->label('Kode Skema')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('tuk')
                                                    ->label('TUK')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_name')
                                                    ->label('Nama Asesor')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessee_name')
                                                    ->label('Nama Asesi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('assessment_date_start')
                                                    ->label('Tanggal Asesmen (Mulai)')
                                                    ->required(),
                                                Forms\Components\DatePicker::make('assessment_date_end')
                                                    ->label('Tanggal Asesmen (Selesai)')
                                                    ->required(),
                                            ]),
                                    ]),

                                // ── Umpan Balik Asesmen ───────────────────────
                                Forms\Components\Section::make('Umpan Balik Asesmen')
                                    ->description('Asesi memberikan respons terhadap setiap komponen berikut.')
                                    ->schema([
                                        Forms\Components\Repeater::make('feedback_items')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(12)
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('component')
                                                            ->label('Komponen Umpan Balik')
                                                            ->content(fn ($get) => $get('component'))
                                                            ->columnSpan(7),

                                                        Forms\Components\Toggle::make('result_yes')
                                                            ->label('Ya')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('success')
                                                            ->inline(false)
                                                            ->columnSpan(1),

                                                        Forms\Components\Toggle::make('result_no')
                                                            ->label('Tidak')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('danger')
                                                            ->inline(false)
                                                            ->columnSpan(1),

                                                        Forms\Components\Textarea::make('assessee_note')
                                                            ->label('Catatan Asesi')
                                                            ->rows(2)
                                                            ->columnSpan(3),
                                                    ]),
                                                Forms\Components\Hidden::make('component'),
                                            ])
                                            ->default(self::defaultFeedbackItems())
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(false),
                                    ]),

                                // ── Catatan Tambahan ──────────────────────────
                                Forms\Components\Section::make('Catatan Tambahan')
                                    ->statePath('additional_footer')
                                    ->schema([
                                        Forms\Components\Textarea::make('other_comments')
                                            ->label('Komentar/Catatan Lainnya (apabila ada)')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Tanda Tangan')
                                    ->schema([
                                        self::standardAsesiDisclaimer(),
                                        self::standardAssessorSignatureBlock('signatures.assessor'),
                                    ]),
                            ]),
                    ]),
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
                    ->label('Reg. ID')
                    ->sortable()
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(35),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
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

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAk03s::route('/'),
        ];
    }

    // ── Default feedback items (10 fixed components from FR.AK.03) ─────────────
    private static function defaultFeedbackItems(): array
    {
        return [
            [
                'component'     => 'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
            [
                'component'     => 'Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen',
                'result_yes'    => null,
                'result_no'     => null,
                'assessee_note' => '',
            ],
        ];
    }
}
