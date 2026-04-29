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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.05B - LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate = $reg->candidate?->name ?? 'Unknown';
                                            $scheme = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
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

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                    ]),

                                // ================== MASTER DATA KELOMPOK & UNIT (READ ONLY) ==================
                                Forms\Components\Section::make('Kelompok Pekerjaan dan Unit Kompetensi')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('group_name')
                                                    ->label('Nama Kelompok Pekerjaan')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->columnSpanFull(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
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
                                                Forms\Components\TextInput::make('question_no')
                                                    ->label('No. Soal')
                                                    ->numeric()
                                                    ->required()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../answer_keys') ?? []) + 1),

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

                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::standardAsesiDisclaimer(),
                                self::standardAssessorSignatureBlock('main_data.signatures.assessor'),
                            ]),
                    ]),
            ]);
    }

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

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.05B'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa05bs::route('/'),
            'create' => Pages\CreateFrIa05b::route('/create'),
            'edit' => Pages\EditFrIa05b::route('/{record}/edit'),
        ];
    }
}