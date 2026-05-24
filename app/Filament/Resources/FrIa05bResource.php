<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa05bResource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use App\Models\CompetencyUnit;
use App\Models\User;
use App\Models\UserSignature;
use App\Models\WorkGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class FrIa05bResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'IA (B)';
    protected static ?string $navigationLabel = 'FR.IA.05b';
    protected static ?string $modelLabel = 'FR.IA.05b';
    protected static ?string $pluralModelLabel = 'FR.IA.05b';
    protected static ?int $navigationSort = 2;

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
                Forms\Components\Section::make('FR.IA.05B - LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(fn (\Illuminate\Database\Eloquent\Model $record) => static::getFilteredRegistrationOptions($record))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa05bData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa05bData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.05B'),

                        Forms\Components\Group::make()->statePath('main_data')
                            ->schema([

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                    ]),

                                // ================== MASTER DATA KELOMPOK & UNIT (READ ONLY) ==================
                                Forms\Components\Section::make('Kelompok Pekerjaan dan Unit Kompetensi')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('group_name')
                                                    ->label('Nama Kelompok Pekerjaan')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->columnSpanFull(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated()->columnSpan(2),
                                                        Forms\Components\Textarea::make('kuk')
                                                            ->label('Kriteria Unjuk Kerja (KUK)')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->rows(3)
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->columns(3)
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->addActionLabel('Unit dari Master Data')
                                                    ->extraAttributes(['style' => 'max-height: 300px; overflow-y: auto;']),
                                            ])
                                            ->disabled()
                                            ->dehydrated()
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->cloneable(false)
                                            ->addActionLabel('Kelompok Pekerjaan dari Master Data'),
                                    ]),

                                // ================== KUNCI JAWABAN (Dropdown Abjad) ==================
                                Forms\Components\Section::make('Kunci Jawaban Pertanyaan Tertulis Pilihan Ganda')
                                    ->schema([
                                        Forms\Components\Repeater::make('answer_keys')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('question_no')
                                                    ->label('No. Soal')
                                                    ->numeric()
                                                    ->required()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../answer_keys') ?? []) + 1),

                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                Forms\Components\Select::make('key')
                                                    ->label('Kunci Jawaban')
                                                    ->options([
                                                        'A' => 'A',
                                                        'B' => 'B',
                                                        'C' => 'C',
                                                        'D' => 'D',
                                                        'E' => 'E',
                                                    ])
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['question_no'] = $i++;
                                                }
                                                $set('answer_keys', $state);
                                            })
                                            ->addActionLabel('Tambah Kunci Jawaban'),
                                    ]),

                                self::standardDocumentControlBlock('document_control'),
                            ]),

                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::standardAsesiDisclaimer(),
                                self::standardAssessorSignatureBlock('main_data.signatures.assessor'),
                            ]),
                    ]),
            ]);
    }

    /**
     * Populate ia05b data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa05bData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with('scheme')->find($state);
        if ($reg && $reg->scheme) {
            $set('main_data.header.certification_scheme', $reg->scheme->name);
            $set('main_data.header.scheme_number', $reg->scheme->code ?? '');
            $set('main_data.header.tuk', 'Sewaktu/Tempat Kerja/Mandiri');

            // Populate work_groups if empty
            $existingGroups = $get ? (array) $get('main_data.work_groups') : [];
            if ($isUpdate || empty($existingGroups) || empty(array_filter(array_column($existingGroups, 'group_name')))) {
                $workGroups = self::getMasterWorkGroups($reg->scheme_id);
                $set('main_data.work_groups', $workGroups);
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }
        }
    }

    /**
     * Fetch Master Data Kelompok Pekerjaan + Unit Kompetensi
     */
    private static function getMasterWorkGroups($schemeId): array
    {
        if (!$schemeId) return [];

        $workGroups = WorkGroup::where('scheme_id', $schemeId)
            ->with(['competencyUnits' => function ($query) {
                $query->orderBy('unit_code');
            }])
            ->orderBy('id')
            ->get();

        $result = [];

        foreach ($workGroups as $wg) {
            $units = [];
            foreach ($wg->competencyUnits as $unit) {
                $kukText = $unit->competencyElements
                    ->flatMap(fn($el) => $el->performanceCriteria->pluck('description'))
                    ->implode("\n");

                $units[] = [
                    'no'         => $unit->id, // atau tambahkan kolom order jika ada
                    'unit_code'  => $unit->unit_code,
                    'unit_title' => $unit->unit_title,
                    'kuk'        => $kukText ?: '-',
                ];
            }

            $result[] = [
                'group_name' => $wg->name,
                'units'      => $units,
            ];
        }

        return $result;
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.05B'))
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable()->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFrIa05bs::route('/'),
            'create' => Pages\CreateFrIa05b::route('/create'),
            'edit' => Pages\EditFrIa05b::route('/{record}/edit'),
        ];
    }
}