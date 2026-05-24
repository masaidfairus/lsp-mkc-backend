<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa06cResource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use App\Models\CompetencyUnit;
use App\Models\User;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class FrIa06cResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    
    protected static ?string $navigationGroup = 'IA (C)';
    protected static ?string $navigationLabel = 'FR.IA.06c';
    protected static ?string $modelLabel = 'FR.IA.06c';
    protected static ?string $pluralModelLabel = 'FR.IA.06c';
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
                Forms\Components\Section::make('FR.IA.06C - LEMBAR JAWABAN PERTANYAAN TERTULIS ESAI')
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
                                            self::populateIa06cData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa06cData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.06C'),

                        Forms\Components\Group::make()->statePath('main_data')
                            ->schema([

                                // ================== HEADER ==================
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
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false),
                                        Forms\Components\TimePicker::make('time')->label('Waktu')->native(false),
                                    ]),

                                // ================== LEMBAR JAWABAN ESAI ==================
                                Forms\Components\Section::make('Lembar Jawaban Esai')
                                    ->schema([
                                        Forms\Components\Repeater::make('answer_sheet')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No.')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../answer_sheet') ?? []) + 1),
                                                Forms\Components\Textarea::make('answer_text')
                                                    ->label('Jawaban Asesi')
                                                    ->rows(4)
                                                    ->required()
                                                    ->columnSpanFull(),
                                                Forms\Components\Group::make([
                                                    Forms\Components\Radio::make('achievement.is_yes')
                                                        ->label('Pencapaian')
                                                        ->options([true => 'Ya', false => 'Tidak'])
                                                        ->inline()
                                                ])->columnSpanFull(),
                                            ])
                                            ->columns(1)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('answer_sheet', $state);
                                            })
                                            ->addActionLabel('Tambah Jawaban Esai')
                                            ->default(array_map(fn($i) => [
                                                'no' => $i,
                                                'answer_text' => '',
                                                'achievement' => ['is_yes' => null, 'is_no' => null]
                                            ], range(1, 5))),
                                    ]),

                                // ================== UMPAN BALIK ==================
                                Forms\Components\Section::make('Umpan Balik dan Catatan')
                                    ->schema([
                                        Forms\Components\Textarea::make('feedback_section.assessee_feedback')
                                            ->label('Umpan Balik untuk Asesi')
                                            ->rows(3),

                                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                        Forms\Components\Select::make('feedback_section.knowledge_aspect_status')
                                            ->label('Aspek Pengetahuan')
                                            ->options([
                                                'tercapai' => 'Tercapai',
                                                'belum_tercapai' => 'Belum Tercapai',
                                            ]),

                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('feedback_section.unachieved_details')
                                            ->label('Unit / Elemen / KUK yang belum tercapai')
                                            ->columnSpanFull(),
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
     * Populate ia06c data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa06cData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', 'Sewaktu/Tempat Kerja/Mandiri');
            $set('main_data.header.assessor_name', $reg->assessor?->name ?? '');
            $set('main_data.header.assessee_name', $reg->candidate?->name ?? '');
            
            if ($get && !$get('main_data.header.date')) {
                $set('main_data.header.date', now()->format('Y-m-d'));
            }
            if ($get && !$get('main_data.header.time')) {
                $set('main_data.header.time', now()->format('H:i'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            $units = self::getSchemeUnitsForDropdown($reg->scheme_id);
            $set('unachieved_units', $units);
        }
    }

    /**
     * Mengambil scheme units for dropdown.
     *
     * @param  mixed  $schemeId
     * @return array
     */
    private static function getSchemeUnitsForDropdown($schemeId): array
    {
        if (!$schemeId) return [];

        $units = CompetencyUnit::where('scheme_id', $schemeId)
            ->orderBy('unit_code')
            ->get();

        $options = [];
        foreach ($units as $unit) {
            $key = "{$unit->unit_code} - {$unit->unit_title}";
            $options[$key] = $key;
        }
        return $options;
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.06C'))
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable()->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa06cs::route('/'),
            'create' => Pages\CreateFrIa06c::route('/create'),
            'edit' => Pages\EditFrIa06c::route('/{record}/edit'),
        ];
    }
}