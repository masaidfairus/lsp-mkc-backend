<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa04aResource\Pages;
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
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = AssessmentRegistration::with([
                                                'scheme.competencyUnits.workGroup',
                                                'candidate',
                                                'assessor'
                                            ])->find($state);

                                            if ($reg) {
                                                $set('fria04a_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fria04a_data.header.scheme_code', $reg->scheme?->code);
                                                $set('fria04a_data.header.tuk', $reg->tuk ?? '');
                                                $set('fria04a_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fria04a_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fria04a_data.header.assessment_date', now()->format('Y-m-d'));
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.04A'),

                        Forms\Components\Group::make()
                            ->statePath('fria04a_data')
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
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled(),
                                                    ])
                                                    ->columns(3)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),

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

                                Forms\Components\Section::make('Kontrol Dokumen')
                                    ->schema([
                                        Forms\Components\Repeater::make('document_control.compilers')
                                            ->label('Penyusun')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->label('No')->numeric(),
                                                Forms\Components\TextInput::make('name')->label('Nama'),
                                                Forms\Components\TextInput::make('met_no')->label('No. MET'),
                                                Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false),
                                                Forms\Components\TextInput::make('signature')->label('Tanda Tangan'),
                                            ])
                                            ->columns(5)
                                            ->addActionLabel('Tambah Penyusun'),

                                        Forms\Components\Repeater::make('document_control.validators')
                                            ->label('Validator')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->label('No')->numeric(),
                                                Forms\Components\TextInput::make('name')->label('Nama'),
                                                Forms\Components\TextInput::make('met_no')->label('No. MET'),
                                                Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false),
                                                Forms\Components\TextInput::make('signature')->label('Tanda Tangan'),
                                            ])
                                            ->columns(5)
                                            ->addActionLabel('Tambah Validator'),
                                    ]),
                            ]),

                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::assessorSignatureBlock(),
                                self::supervisorSignatureBlock(),
                            ]),
                    ]),
            ]);
    }

    // ... (table, pages, signature methods tetap sama seperti sebelumnya)
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
            ->actions([Tables\Actions\EditAction::make()])
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

    private static function assessorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesor')
            ->statePath('fria04a_data.signatures.assessor')
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
                            $set('signature_url', self::resolveSignatureUrl($user->signature?->signature_image ?? null));
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('signature_url'),
                Forms\Components\Placeholder::make('preview')->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
            ]);
    }

    private static function supervisorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Supervisor / Atasan')
            ->statePath('fria04a_data.signatures.supervisor')
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama Supervisor'),
                Forms\Components\Hidden::make('signature_url'),
                Forms\Components\Placeholder::make('preview')->content(fn($get) => self::renderSignaturePreview($get, 'Supervisor')),
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
            return new HtmlString('<div class="text-gray-400 italic text-sm">Isi ' . $role . ' untuk preview</div>');
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
                <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center;'>
                    {$imgHtml}
                </div>
            </div>
        </div>";

        return new HtmlString($html);
    }
}