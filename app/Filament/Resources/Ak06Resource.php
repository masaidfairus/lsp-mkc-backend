<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak06Resource\Pages;
use App\Models\Ak06;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak06Resource extends Resource
{
    use \App\Filament\Traits\HasStandardIaForm;
    protected static ?string $model = Ak06::class;
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.06';
    protected static ?string $modelLabel = 'AK.06';
    protected static ?string $pluralModelLabel = 'AK.06';
    protected static ?int $navigationSort = 6;
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
                                    ->options(fn (?\Illuminate\Database\Eloquent\Model $record) => static::getFilteredRegistrationOptions($record))
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
                                            $set('main_data.assessor_footer.lead_assessor_name', $reg->assessor->name);
                                            
                                            // Auto-populate signature
                                            self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.assessor_footer');
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
                                                Forms\Components\DatePicker::make('assessment_date')
                                                    ->label('Tanggal Asesmen')
                                                    ->default(fn () => now()->toDateString())
                                                    ->required(),
                                            ]),
                                    ]),

                                // ── Tinjauan Prinsip-Prinsip Asesmen ──────────
                                Forms\Components\Section::make('Tinjauan Prinsip-Prinsip Asesmen')
                                    ->statePath('assessment_principles_review')
                                    ->schema([
                                        Forms\Components\Repeater::make('items')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(12)
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('aspect_name')
                                                            ->label('Aspek/Prosedur')
                                                            ->content(fn ($get) => $get('aspect_name'))
                                                            ->columnSpan(4),
                                                        Forms\Components\Toggle::make('validitas')
                                                            ->label('Validitas')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('success')
                                                            ->inline(false)
                                                            ->columnSpan(2),
                                                        Forms\Components\Toggle::make('reliabel')
                                                            ->label('Reliabilitas')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('success')
                                                            ->inline(false)
                                                            ->columnSpan(2),
                                                        Forms\Components\Toggle::make('fleksibel')
                                                            ->label('Fleksibilitas')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('success')
                                                            ->inline(false)
                                                            ->columnSpan(2),
                                                        Forms\Components\Toggle::make('adil')
                                                            ->label('Keadilan')
                                                            ->onIcon('heroicon-m-check')
                                                            ->offIcon('heroicon-m-x-mark')
                                                            ->onColor('success')
                                                            ->inline(false)
                                                            ->columnSpan(2),
                                                    ]),
                                                Forms\Components\Hidden::make('aspect_name'),
                                            ])
                                            ->default(self::defaultPrincipleItems())
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(false),

                                        Forms\Components\Textarea::make('recommendation_notes')
                                            ->label('Rekomendasi untuk Peningkatan')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // ── Pemenuhan Dimensi Kompetensi ──────────────
                                Forms\Components\Section::make('Tinjauan Pemenuhan Dimensi Kompetensi')
                                    ->statePath('competency_dimensions_review')
                                    ->schema([
                                        Forms\Components\Repeater::make('items')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Textarea::make('aspect_name')
                                                    ->label('Aspek yang Ditinjau')
                                                    ->rows(2)
                                                    ->columnSpanFull(),
                                                Forms\Components\Grid::make(5)
                                                    ->schema([
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('task_skills')
                                                            ->label('Keterampilan Tugas'),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('task_management_skills')
                                                            ->label('Keterampilan Manajemen Tugas'),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('contingency_management_skills')
                                                            ->label('Keterampilan Kontingensi'),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('job_role_environment_skills')
                                                            ->label('Keterampilan Lingkungan/Peran'),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('transfer_skills')
                                                            ->label('Keterampilan Transfer'),
                                                    ]),
                                            ])
                                            ->default(self::defaultDimensionItems())
                                            ->addActionLabel('Tambah Aspek Dimensi')
                                            ->reorderable(false),

                                        Forms\Components\Textarea::make('recommendation_notes')
                                            ->label('Rekomendasi untuk Peningkatan')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // ── Footer Asesor ─────────────────────────────
                                Forms\Components\Section::make('Tanda Tangan Asesor Utama')
                                    ->statePath('assessor_footer')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('lead_assessor_name')
                                            ->label('Nama Asesor Utama')
                                            ->disabled()
                                            ->dehydrated(),

                                        Forms\Components\Textarea::make('comment')
                                            ->label('Komentar Asesor')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        self::standardAssessorSignatureBlock(''),
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
            'index' => Pages\ManageAk06s::route('/'),
        ];
    }

    // ── Default items for assessment principles review (5 fixed rows) ──────────
    private static function defaultPrincipleItems(): array
    {
        return [
            ['aspect_name' => 'Rencana asesmen',      'validitas' => false, 'reliabel' => false, 'fleksibel' => false, 'adil' => false],
            ['aspect_name' => 'Persiapan asesmen',    'validitas' => false, 'reliabel' => false, 'fleksibel' => false, 'adil' => false],
            ['aspect_name' => 'Implementasi asesmen', 'validitas' => false, 'reliabel' => false, 'fleksibel' => false, 'adil' => false],
            ['aspect_name' => 'Keputusan asesmen',    'validitas' => false, 'reliabel' => false, 'fleksibel' => null,  'adil' => false],
            ['aspect_name' => 'Umpan balik asesmen',  'validitas' => false, 'reliabel' => false, 'fleksibel' => null,  'adil' => false],
        ];
    }

    // ── Default first dimension review item ────────────────────────────────────
    private static function defaultDimensionItems(): array
    {
        return [
            [
                'aspect_name'                   => 'Konsistensi keputusan asesmen (Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi)',
                'task_skills'                   => '',
                'task_management_skills'        => '',
                'contingency_management_skills' => '',
                'job_role_environment_skills'   => '',
                'transfer_skills'               => '',
            ],
        ];
    }
}
