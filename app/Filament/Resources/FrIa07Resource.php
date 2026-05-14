<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa07Resource\Pages;
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

class FrIa07Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.07';
    protected static ?string $modelLabel = 'FR.IA.07';
    protected static ?string $pluralModelLabel = 'FR.IA.07';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.07 - Daftar Pertanyaan Lisan (DPL)')
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
                                            self::populateIa07Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa07Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.07'),

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
                                        Forms\Components\TextInput::make('assessment_date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                Forms\Components\Section::make('Kelompok Pekerjaan')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_group_mapping')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('group_name')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->disabled()
                                                    ->dehydrated(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->numeric()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated(),
                                                    ])
                                                    ->columns(3)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['group_name'] ?? 'Kelompok'),
                                    ]),

                                Forms\Components\Section::make('Daftar Pertanyaan Lisan')
                                    ->schema([
                                        Forms\Components\Repeater::make('oral_questions')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')
                                                    ->label('No')
                                                    ->numeric()
                                                    ->default(fn ($get) => count($get('../oral_questions') ?? []) + 1),
                                                Forms\Components\Textarea::make('question_text')->label('Pertanyaan')->rows(3)->columnSpan(3),
                                                Forms\Components\Textarea::make('answer_key')->label('Kunci Jawaban')->rows(2)->columnSpan(2),
                                                Forms\Components\Textarea::make('assessee_answer')->label('Jawaban Asesi')->rows(2)->columnSpan(2),
                                                Forms\Components\Group::make([
                                                    Forms\Components\Radio::make('achievement.is_yes')
                                                        ->label('Pencapaian')
                                                        ->options([true => 'Ya', false => 'Tidak'])
                                                        ->inline()
                                                ])->columnSpanFull(),
                                            ])
                                            ->columns(7)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if (!is_array($state)) return;
                                                $i = 1;
                                                foreach ($state as $key => $item) {
                                                    $state[$key]['no'] = $i++;
                                                }
                                                $set('oral_questions', $state);
                                            })
                                            ->addActionLabel('Tambah Pertanyaan'),
                                    ]),

                                Forms\Components\Section::make('Umpan Balik')
                                    ->statePath('feedback_section')
                                    ->schema([
                                        Forms\Components\Textarea::make('assessee_feedback_summary')->label('Umpan Balik untuk Asesi')->rows(3),
                                        Forms\Components\Radio::make('knowledge_aspect_status')
                                            ->label('Aspek Pengetahuan')
                                            ->options(['tercapai' => 'Tercapai', 'belum_tercapai' => 'Belum Tercapai'])
                                            ->inline(),
                                        Forms\Components\Textarea::make('unachieved_details')->label('Detail yang Belum Tercapai')->rows(2),
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

    protected static function populateIa07Data($state, callable $set, callable $get = null, bool $isUpdate = false)
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

            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('d-m-Y'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            // Only repopulate work_group_mapping if empty or user changed registration
            $existingGroups = $get ? (array) $get('main_data.work_group_mapping') : [];
            if (($isUpdate || empty($existingGroups) || empty(array_filter(array_column($existingGroups, 'group_name')))) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $grouped = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Lainnya');

                $workGroups = [];
                foreach ($grouped as $groupName => $groupUnits) {
                    $workGroups[] = [
                        'group_name' => $groupName,
                        'units' => $groupUnits->map(fn($u, $idx) => [
                            'no'        => $idx + 1,
                            'unit_code' => $u->unit_code,
                            'unit_title'=> $u->unit_title,
                        ])->toArray(),
                    ];
                }
                $set('main_data.work_group_mapping', $workGroups);
            }
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.07'))
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
            'index' => Pages\ListFrIa07s::route('/'),
            'create' => Pages\CreateFrIa07::route('/create'),
            'edit' => Pages\EditFrIa07::route('/{record}/edit'),
        ];
    }
}