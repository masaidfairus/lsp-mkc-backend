<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa03Resource\Pages;
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

class FrIa03Resource extends Resource
{
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.03';
    protected static ?string $modelLabel = 'FR.IA.03';
    protected static ?string $pluralModelLabel = 'FR.IA.03';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.03 - Observasi dan Pertanyaan Pendukung')
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
                                                $set('fr_ia_03_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fr_ia_03_data.header.scheme_number', $reg->scheme?->code);
                                                $set('fr_ia_03_data.header.tuk', $reg->tuk ?? '');
                                                $set('fr_ia_03_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fr_ia_03_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fr_ia_03_data.header.date', now()->format('d-m-Y'));

                                                // Populate work_groups
                                                if ($reg->scheme) {
                                                    $units = $reg->scheme->competencyUnits;
                                                    $grouped = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Kelompok Pekerjaan');

                                                    $workGroups = [];
                                                    $groupNo = 1;
                                                    foreach ($grouped as $wgName => $wgUnits) {
                                                        $workGroups[] = [
                                                            'group_label' => "Kelompok Pekerjaan {$groupNo}",
                                                            'group_name' => $wgName,
                                                            'units' => $wgUnits->map(fn($u, $idx) => [
                                                                'no' => $idx + 1,
                                                                'unit_code' => $u->unit_code,
                                                                'unit_title' => $u->unit_title,
                                                            ])->toArray(),
                                                            'observation_questions' => [
                                                                ['no' => 1, 'question' => '', 'response' => '', 'achievement' => ['is_yes' => false, 'is_no' => false]],
                                                                ['no' => 2, 'question' => '', 'response' => '', 'achievement' => ['is_yes' => false, 'is_no' => false]],
                                                            ],
                                                        ];
                                                        $groupNo++;
                                                    }
                                                    $set('fr_ia_03_data.work_groups', $workGroups);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.03'),

                        Forms\Components\Group::make()
                            ->statePath('fr_ia_03_data')
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

                                Forms\Components\Section::make('Kelompok Pekerjaan & Pertanyaan Observasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Placeholder::make('group_label')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->content(fn($get) => new HtmlString('<strong class="text-primary-600">' . ($get('group_label') ?? '-') . '</strong>')),

                                                Forms\Components\TextInput::make('group_name')
                                                    ->label('Nama Kelompok')
                                                    ->disabled()
                                                    ->dehydrated(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated(),
                                                    ])
                                                    ->columns(3)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),

                                                Forms\Components\Repeater::make('observation_questions')
                                                    ->label('Pertanyaan Pendukung Observasi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        Forms\Components\Textarea::make('question')->label('Pertanyaan')->rows(2)->columnSpan(2),
                                                        Forms\Components\Textarea::make('response')->label('Respons Asesi')->rows(2)->columnSpan(2),
                                                        Forms\Components\Checkbox::make('achievement.is_yes')->label('Ya'),
                                                        Forms\Components\Checkbox::make('achievement.is_no')->label('Tidak'),
                                                    ])
                                                    ->columns(5)
                                                    ->addActionLabel('Tambah Pertanyaan')
                                                    ->default([]),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['group_label'] ?? 'Kelompok'),
                                    ]),

                                Forms\Components\Textarea::make('assessee_feedback')
                                    ->label('Umpan Balik Asesi')
                                    ->rows(3),
                            ]),

                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::assesseeSignatureBlock(),
                                self::assessorSignatureBlock(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.03'))
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
            'index' => Pages\ListFrIa03s::route('/'),
            'create' => Pages\CreateFrIa03::route('/create'),
            'edit' => Pages\EditFrIa03::route('/{record}/edit'),
        ];
    }

    private static function assessorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesor')
            ->statePath('fr_ia_03_data.signatures.assessor')
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
                            $set('reg_no', $user->registration_number ?? '');
                            $set('date', now()->format('d-m-Y'));

                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) {
                                $set('signature_url', self::resolveSignatureUrl($sig->signature_image));
                            }
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_no'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature_url'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
            ]);
    }

    private static function assesseeSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesi')
            ->statePath('fr_ia_03_data.signatures.assessee')
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama Asesi')->disabled()->dehydrated(),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature_url'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, 'Asesi')),
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
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih ' . $role . ' untuk preview</div>');
        }

        $date = $get('date') ?? now()->format('d-m-Y');
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
                <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center;'>
                    <div style='position: relative; z-index: 10;'>{$imgHtml}</div>
                </div>
                <p style='text-align: center; margin-top: 8px; font-size: 13px;'>{$date}</p>
            </div>
        </div>";

        return new HtmlString($html);
    }
}