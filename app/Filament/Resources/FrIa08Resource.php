<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa08Resource\Pages;
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

class FrIa08Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.08';
    protected static ?string $modelLabel = 'FR.IA.08';
    protected static ?string $pluralModelLabel = 'FR.IA.08';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.08 - CVP - Ceklis Verifikasi Portofolio')
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
                                            self::populateIa08Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa08Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.08'),

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

                                Forms\Components\Section::make('Verifikasi Bukti Portofolio')
                                    ->schema([
                                        Forms\Components\Repeater::make('portfolio_verification')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No')
                                                    ->numeric()
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->default(fn (Forms\Get $get) => count($get('../portfolio_verification') ?? []) + 1),
                                                Forms\Components\Textarea::make('portfolio_evidence_name')->label('Bukti Portofolio')->rows(2)->columnSpan(3),

                                                Forms\Components\Section::make('Aturan Bukti')
                                                    ->columns(4)
                                                    ->schema([
                                                        Forms\Components\Radio::make('rules.valid')
                                                            ->label('Valid')
                                                            ->options(['ya' => 'Ya', 'tidak' => 'Tidak'])
                                                            ->inline(),
                                                        Forms\Components\Radio::make('rules.authentic')
                                                            ->label('Asli')
                                                            ->options(['ya' => 'Ya', 'tidak' => 'Tidak'])
                                                            ->inline(),
                                                        Forms\Components\Radio::make('rules.current')
                                                            ->label('Terkini')
                                                            ->options(['ya' => 'Ya', 'tidak' => 'Tidak'])
                                                            ->inline(),
                                                        Forms\Components\Radio::make('rules.sufficient')
                                                            ->label('Memadai')
                                                            ->options(['ya' => 'Ya', 'tidak' => 'Tidak'])
                                                            ->inline(),
                                                    ]),
                                            ])
                                            ->columns(5)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('portfolio_verification', $state);
                                            })
                                            ->addActionLabel('Tambah Bukti Portofolio'),
                                    ]),

                                Forms\Components\Section::make('Tindak Lanjut Verifikasi Bukti')
                                    ->schema([
                                        Forms\Components\Repeater::make('follow_up_verification')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Checkbox::make('is_checked')->label('Cek'),
                                                Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                Forms\Components\TextInput::make('element_no')->label('No. Elemen'),
                                                Forms\Components\Textarea::make('interview_substance')->label('Materi/Substansi Wawancara')->rows(2)->columnSpan(3),
                                            ])
                                            ->columns(6)
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->itemLabel(fn(array $state): ?string => $state['unit_code'] ?? ''),
                                    ]),

                                Forms\Components\Textarea::make('additional_evidence_details')
                                    ->label('Bukti Tambahan Diperlukan')
                                    ->rows(3),

                                Forms\Components\Section::make('Rekomendasi Asesor')
                                    ->schema([
                                        Forms\Components\Radio::make('assessor_recommendation.is_competent')
                                            ->label('Rekomendasi')
                                            ->options([true => 'KOMPETEN', false => 'Observasi Langsung Diperlukan'])
                                            ->inline()
                                            ->live(),

                                        Forms\Components\Group::make()
                                            ->visible(fn($get) => $get('assessor_recommendation.is_competent') === false)
                                            ->schema([
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.work_group')->label('Kelompok Pekerjaan'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.unit')->label('Unit'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.element')->label('Elemen'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.kuk')->label('KUK'),
                                            ])
                                            ->columns(2),
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

    protected static function populateIa08Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits.workGroup',
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

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            // Only repopulate follow_up_verification if empty or user changed registration
            $existingFollowUps = $get ? (array) $get('main_data.follow_up_verification') : [];
            if (($isUpdate || empty($existingFollowUps) || empty(array_filter(array_column($existingFollowUps, 'unit_code')))) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $followUps = $units->map(fn($u) => [
                    'is_checked' => false,
                    'unit_code' => $u->unit_code,
                    'element_no' => '',
                    'interview_substance' => '',
                ])->toArray();
                $set('main_data.follow_up_verification', $followUps);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.08'))
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
            'index' => Pages\ListFrIa08s::route('/'),
            'create' => Pages\CreateFrIa08::route('/create'),
            'edit' => Pages\EditFrIa08::route('/{record}/edit'),
        ];
    }
}