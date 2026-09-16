<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa06aResource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use App\Models\User;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class FrIa06aResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'IA (A)';
    protected static ?string $navigationLabel = 'FR.IA.06a';
    protected static ?string $modelLabel = 'FR.IA.06a';
    protected static ?string $pluralModelLabel = 'FR.IA.06a';
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
                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('FR.IA.06A - DPT - Daftar Pertanyaan Tertulis Esai')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(fn (?\Illuminate\Database\Eloquent\Model $record) => static::getFilteredRegistrationOptionsByMapaMethod($record, ['DPT']))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa06aData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa06aData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.06A'),

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
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('assessment_date')->label('Tanggal')->native(false),
                                        Forms\Components\TimePicker::make('assessment_time')->label('Waktu')->native(false),
                                    ]),

                                // Daftar Pertanyaan Esai
                                Forms\Components\Section::make('Daftar Pertanyaan Esai')
                                    ->schema([
                                        Forms\Components\Repeater::make('questions_list')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../questions_list') ?? []) + 1),
                                                Forms\Components\Textarea::make('question_text')->label('Pertanyaan Esai')->rows(4)->columnSpanFull(),
                                            ])
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('questions_list', $state);
                                            })
                                            ->addActionLabel('Tambah Pertanyaan')
                                            ->itemLabel(fn(array $state): ?string => 'Pertanyaan ' . ($state['no'] ?? '')),
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
     * Populate ia06a data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa06aData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);
            
            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('Y-m-d'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }
        }
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.06A'))
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
            'index' => Pages\ListFrIa06as::route('/'),
            'create' => Pages\CreateFrIa06a::route('/create'),
            'edit' => Pages\EditFrIa06a::route('/{record}/edit'),
        ];
    }
}