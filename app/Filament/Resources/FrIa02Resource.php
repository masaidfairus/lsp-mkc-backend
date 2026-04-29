<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa02Resource\Pages;
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

class FrIa02Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.02';
    protected static ?string $modelLabel = 'FR.IA.02';
    protected static ?string $pluralModelLabel = 'FR.IA.02';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.02. TPD - Tugas Praktik Demonstrasi')
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
                                            self::populateIa02Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa02Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.02'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Judul Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessment_date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                // Demonstration Tasks sesuai payload
                                Forms\Components\Section::make('Tugas Praktik Demonstrasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('demonstration_tasks')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Placeholder::make('work_group_label')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->content(fn($get) => new HtmlString('<strong class="text-primary-600">' . ($get('work_group') ?? '-') . '</strong>')),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated(),
                                                    ])
                                                    ->columns(2)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement()
                                                    ->extraAttributes(['style' => 'max-height: 200px; overflow-y: auto;']),

                                                Forms\Components\Textarea::make('scenario')
                                                    ->label('Skenario / Petunjuk Tugas Demonstrasi')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Repeater::make('equipments')
                                                    ->label('Peralatan / Perlengkapan')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('item')->label('Peralatan'),
                                                    ])
                                                    ->columns(1)
                                                    ->addActionLabel('Tambah Peralatan')
                                                    ->default([]),

                                                Forms\Components\TextInput::make('time_allocation')
                                                    ->label('Alokasi Waktu (contoh: 120 Menit)'),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['work_group'] ?? 'Tugas Demonstrasi'),
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

    protected static function populateIa02Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits.workGroup',
            'candidate',
            'assessor'
        ])->find($state);

        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_code', $reg->scheme?->code);
            $set('main_data.header.assessee_name', $reg->candidate?->name);
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            
            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('d-m-Y'));
            }

            // Populate demonstration_tasks if empty
            $existingTasks = $get ? (array) $get('main_data.demonstration_tasks') : [];
            if (($isUpdate || empty($existingTasks) || empty(array_filter(array_column($existingTasks, 'work_group')))) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $grouped = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Unit Kompetensi');

                $tasks = [];
                foreach ($grouped as $wgName => $wgUnits) {
                    $tasks[] = [
                        'work_group' => $wgName,
                        'units' => $wgUnits->map(fn($u) => [
                            'unit_code' => $u->unit_code,
                            'unit_title' => $u->unit_title,
                        ])->toArray(),
                        'scenario' => '',
                        'equipments' => [],
                        'time_allocation' => '',
                    ];
                }
                $set('main_data.demonstration_tasks', $tasks);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.02'))
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
            'index' => Pages\ListFrIa02s::route('/'),
            'create' => Pages\CreateFrIa02::route('/create'),
            'edit' => Pages\EditFrIa02::route('/{record}/edit'),
        ];
    }
}