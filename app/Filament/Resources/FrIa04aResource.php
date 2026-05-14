<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa04aResource\Pages;
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

class FrIa04aResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationGroup = 'IA (A)';
    protected static ?string $navigationLabel = 'FR.IA.04a';
    protected static ?string $modelLabel = 'FR.IA.04a';
    protected static ?string $pluralModelLabel = 'FR.IA.04a';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.04A - Tugas Praktik Demonstrasi')
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
                                            self::populateIa04aData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa04aData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.04A'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('assessment_date')->label('Tanggal Penilaian')->native(false),
                                    ]),

                                Forms\Components\Section::make('Tugas Praktik Demonstrasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Placeholder::make('group_label')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->content(fn($get) => new HtmlString('<strong class="text-primary-600">Kelompok ' . ($get('group_id') ?? '') . ' - ' . ($get('group_name') ?? '-') . '</strong>')),

                                                Forms\Components\TextInput::make('group_name')->label('Nama Kelompok')->disabled()->dehydrated(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric()->columnSpan(1),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated()->columnSpan(2),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated()->columnSpan(3),
                                                    ])
                                                    ->columns(6)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement()
                                                    ->extraAttributes(['style' => 'max-height: 300px; overflow-y: auto;']),

                                                Forms\Components\Textarea::make('scenario_instructions.content')->label('Skenario Instruksi')->rows(3)->columnSpanFull(),
                                                Forms\Components\TextInput::make('scenario_instructions.time_allocation')->label('Alokasi Waktu Skenario'),

                                                Forms\Components\Textarea::make('demonstration_items.content')->label('Hal yang Perlu Didemonstrasikan')->rows(3)->columnSpanFull(),
                                                Forms\Components\TextInput::make('demonstration_items.time_allocation')->label('Alokasi Waktu Demonstrasi'),

                                                Forms\Components\Textarea::make('assessee_feedback')->label('Umpan Balik Asesi')->rows(2),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => 'Kelompok ' . ($state['group_id'] ?? '')),
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

    protected static function populateIa04aData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits.workGroup',
            'candidate',
            'assessor'
        ])->find($state);

        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_code', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);
            
            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('Y-m-d'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            // Populate work groups if empty (to avoid overwriting user input during edit)
            $existingGroups = $get ? (array) $get('main_data.work_groups') : [];
            if (($isUpdate || empty($existingGroups) || empty(array_filter(array_column($existingGroups, 'group_name')))) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $grouped = $units->groupBy(fn($u) => $u->work_group_id);

                $workGroups = [];
                $idx = 1;
                foreach ($grouped as $wgId => $wgUnits) {
                    $wgName = $wgUnits->first()->workGroup?->name ?? 'Kelompok Pekerjaan';
                    $workGroups[] = [
                        'group_id' => $idx++,
                        'group_name' => $wgName,
                        'units' => $wgUnits->map(fn($u, $i) => [
                            'no' => $i + 1,
                            'unit_code' => $u->unit_code,
                            'unit_title' => $u->unit_title,
                        ])->toArray(),
                        'scenario_instructions' => ['content' => '', 'time_allocation' => ''],
                        'demonstration_items' => ['content' => '', 'time_allocation' => ''],
                        'assessee_feedback' => '',
                    ];
                }
                $set('main_data.work_groups', $workGroups);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.04A'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('Registration ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi')->searchable(),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa04as::route('/'),
            'create' => Pages\CreateFrIa04a::route('/create'),
            'edit' => Pages\EditFrIa04a::route('/{record}/edit'),
        ];
    }
}