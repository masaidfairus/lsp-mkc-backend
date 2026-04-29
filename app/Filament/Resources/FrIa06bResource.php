<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa06bResource\Pages;
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

class FrIa06bResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationGroup = 'IA (B)';
    protected static ?string $navigationLabel = 'FR.IA.06b';
    protected static ?string $modelLabel = 'FR.IA.06b';
    protected static ?string $pluralModelLabel = 'FR.IA.06b';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.06B - LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS ESAI')
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
                                            self::populateIa06bData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa06bData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.06B'),

                        Forms\Components\Group::make()->statePath('main_data')
                            ->schema([

                                // ================== HEADER ==================
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')
                                            ->label('Skema Sertifikasi')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')
                                            ->label('Nomor Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')
                                            ->label('TUK')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')
                                            ->label('Nama Asesor')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\DatePicker::make('assessment_date')
                                            ->label('Tanggal Asesmen')
                                            ->native(false),
                                    ]),

                                // ================== KUNCI JAWABAN ESAI ==================
                                Forms\Components\Section::make('Kunci Jawaban Pertanyaan Esai')
                                    ->schema([
                                        Forms\Components\Repeater::make('answer_keys')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No.')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../answer_keys') ?? []) + 1),

                                                Forms\Components\Textarea::make('key_answer_text')
                                                    ->label('Kunci Jawaban Esai')
                                                    ->rows(5)
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(1)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('answer_keys', $state);
                                            })
                                            ->addActionLabel('Tambah Kunci Jawaban')
                                            ->default([
                                                ['no' => 1, 'key_answer_text' => ''],
                                                ['no' => 2, 'key_answer_text' => ''],
                                                ['no' => 3, 'key_answer_text' => ''],
                                                ['no' => 4, 'key_answer_text' => ''],
                                                ['no' => 5, 'key_answer_text' => ''],
                                            ]),
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

    protected static function populateIa06bData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', 'Sewaktu/Tempat Kerja/Mandiri');
            $set('main_data.header.assessor_name', $reg->assessor?->name ?? '');
            $set('main_data.header.assessee_name', $reg->candidate?->name ?? '');
            
            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('Y-m-d'));
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.06B'))
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
            'index' => Pages\ListFrIa06bs::route('/'),
            'create' => Pages\CreateFrIa06b::route('/create'),
            'edit' => Pages\EditFrIa06b::route('/{record}/edit'),
        ];
    }
}