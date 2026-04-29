<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa09Resource\Pages;
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

class FrIa09Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-microphone';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.09';
    protected static ?string $modelLabel = 'FR.IA.09';
    protected static ?string $pluralModelLabel = 'FR.IA.09';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.09 - PW - Pertanyaan Wawancara')
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
                                            self::populateIa09Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa09Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.09'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                Forms\Components\Section::make('Daftar Bukti Portofolio yang Dirujuk')
                                    ->schema([
                                        Forms\Components\Repeater::make('portfolio_evidence_list')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../portfolio_evidence_list') ?? []) + 1),
                                                Forms\Components\Textarea::make('evidence_name')->label('Nama Bukti Portofolio')->rows(2)->columnSpan(4),
                                            ])
                                            ->columns(5)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('portfolio_evidence_list', $state);
                                            })
                                            ->addActionLabel('Tambah Bukti'),
                                    ]),

                                Forms\Components\Section::make('Pertanyaan Wawancara')
                                    ->schema([
                                        Forms\Components\Repeater::make('interview_questions')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../interview_questions') ?? []) + 1),
                                                Forms\Components\Textarea::make('question')->label('Pertanyaan')->rows(3)->columnSpan(4),
                                                Forms\Components\Textarea::make('assessee_answer_summary')->label('Kesimpulan Jawaban Asesi')->rows(2)->columnSpan(4),
                                                Forms\Components\Group::make([
                                                    Forms\Components\Radio::make('achievement.is_yes')
                                                        ->label('Pencapaian')
                                                        ->options([true => 'Ya', false => 'Tidak'])
                                                        ->inline()
                                                ])->columnSpanFull(),
                                            ])
                                            ->columns(6)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('interview_questions', $state);
                                            })
                                            ->addActionLabel('Tambah Pertanyaan'),
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

    protected static function populateIa09Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits',
            'candidate',
            'assessor'
        ])->find($state);

        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);

            if ($get && !$get('main_data.header.date')) {
                $set('main_data.header.date', now()->format('d-m-Y'));
            }

            // Only reset portfolio_evidence_list if empty or user changed registration
            $existingList = $get ? (array) $get('main_data.portfolio_evidence_list') : [];
            if ($isUpdate || empty($existingList) || empty(array_filter(array_column($existingList, 'evidence_name')))) {
                $set('main_data.portfolio_evidence_list', [
                    ['no' => 1, 'evidence_name' => ''],
                    ['no' => 2, 'evidence_name' => ''],
                    ['no' => 3, 'evidence_name' => ''],
                    ['no' => 4, 'evidence_name' => ''],
                ]);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.09'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('Registration ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi')->searchable(),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa09s::route('/'),
            'create' => Pages\CreateFrIa09::route('/create'),
            'edit' => Pages\EditFrIa09::route('/{record}/edit'),
        ];
    }
}