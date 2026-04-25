<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa07Resource\Pages;
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
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = AssessmentRegistration::with([
                                                'scheme.competencyUnits.workGroup',
                                                'candidate',
                                                'assessor'
                                            ])->find($state);

                                            if ($reg) {
                                                // Header
                                                $set('fria07_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fria07_data.header.scheme_number', $reg->scheme?->code);
                                                $set('fria07_data.header.tuk', $reg->tuk ?? '');
                                                $set('fria07_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fria07_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fria07_data.header.assessment_date', now()->format('d-m-Y'));

                                                // Fetch semua kelompok & unit
                                                if ($reg->scheme) {
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
                                                    $set('fria07_data.work_group_mapping', $workGroups);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.07'),

                        Forms\Components\Group::make()
                            ->statePath('fria07_data')
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
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled(),
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
                                                Forms\Components\Checkbox::make('achievement.is_yes')->label('Ya'),
                                                Forms\Components\Checkbox::make('achievement.is_no')->label('Tidak'),
                                            ])
                                            ->columns(7)
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
                            ]),

                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::assessorSignatureBlock(),
                            ]),
                    ]),
            ]);
    }

    // Table, Pages, dan Signature Helper (sama seperti sebelumnya)
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

    private static function assessorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesor')
            ->statePath('fria07_data.signatures.assessor')
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih Asesor')
                    ->options(User::role('asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('reg_number', $user->registration_number ?? '');
                            $set('signature_and_date', now()->format('d-m-Y'));
                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) $set('signature_url', self::resolveSignatureUrl($sig->signature_image));
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('signature_and_date'),
                Forms\Components\Hidden::make('signature_url'),
                Forms\Components\Placeholder::make('preview')->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
            ]);
    }

    private static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http')) return $path;
        $normalized = preg_replace('#^/?storage/#', '', $path);
        return Storage::url($normalized);
    }

    private static function renderSignaturePreview($get, string $role): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih Asesor untuk preview</div>');
        }
        $signature = $get('signature_url');
        $imgHtml = $signature 
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />" 
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Belum ada tanda tangan</div>";

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>Tanda Tangan {$role}</span>
            </div>
            <div style='padding: 20px;'>
                <p style='font-size: 16px; font-weight: 600; margin: 0 0 14px;'>{$name}</p>
                <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center;'>{$imgHtml}</div>
            </div>
        </div>";
        return new HtmlString($html);
    }
}