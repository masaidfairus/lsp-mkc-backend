<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Mapa02Resource\Pages;
use App\Models\Mapa02;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;

class Mapa02Resource extends Resource
{
    protected static ?string $model = Mapa02::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'MAPA';
    protected static ?string $navigationLabel = 'MAPA.02';
    protected static ?string $modelLabel = 'MAPA.02';
    protected static ?string $pluralModelLabel = 'MAPA.02';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([
                        // ── Header & Registration ──────────────────────────────────────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences   = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key          = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate    = $reg->candidate?->name ?? 'Unknown';
                                            $scheme       = $reg->scheme?->name ?? 'Tanpa Skema';
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
                                                'candidate'
                                            ])->find($state);

                                            if ($reg) {
                                                if ($reg->scheme) {
                                                    $set('mapa02_data.header.certification_scheme', $reg->scheme->name);
                                                    $set('mapa02_data.header.scheme_code', $reg->scheme->code);
                                                    
                                                    // Group units by work group
                                                    $units = $reg->scheme->competencyUnits;
                                                    $groupedUnits = $units->groupBy(function($u) {
                                                        return $u->workGroup ? $u->workGroup->name : 'Unit Kompetensi';
                                                    });

                                                    $sections = [];
                                                    foreach($groupedUnits as $wgName => $wgUnits) {
                                                        $sections[] = [
                                                            'work_group' => $wgName,
                                                            'competency_units' => $wgUnits->map(fn($u) => [
                                                                'unit_code' => $u->unit_code,
                                                                'unit_title' => $u->unit_title,
                                                            ])->toArray(),
                                                            'assessment_instruments' => self::defaultAssessmentInstruments(),
                                                        ];
                                                    }
                                                    $set('mapa02_data.assessment_instruments_section', $sections);
                                                }
                                                $set('mapa02_data.header.assessment_date', now()->format('d-m-Y'));
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Group::make()
                            ->statePath('mapa02_data')
                            ->schema([
                                
                                // ── Skema Sertifikasi ──────────────────────────────────
                                Forms\Components\Section::make('Skema Sertifikasi (KKNI/Okupasi/Klaster)')
                                    ->statePath('header')
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')
                                            ->label('Judul Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')
                                            ->label('Nomor Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('assessment_date')
                                            ->label('Tanggal Asesmen')
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(3),

                                // ── Section: Peta Instrumen Asesmen ─────────────────────
                                Forms\Components\Section::make('PETA INSTRUMEN ASESMEN')
                                    ->description('Hasil Pendekatan Asesmen dan Perencanaan Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_instruments_section')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('work_group_display')
                                                            ->label('Kelompok Pekerjaan')
                                                            ->content(fn ($get) => new HtmlString('<strong class="text-primary-600 text-lg">'.$get('work_group').'</strong>')),
                                                        
                                                        Forms\Components\Repeater::make('competency_units')
                                                            ->label('Unit Kompetensi')
                                                            ->schema([
                                                                Forms\Components\Placeholder::make('unit_info')
                                                                    ->label('')
                                                                    ->content(fn ($get) => new HtmlString('<div class="flex gap-2"><code>'.$get('unit_code').'</code><span>' . $get('unit_title') . '</span></div>')),
                                                            ])
                                                            ->disableItemCreation()
                                                            ->disableItemDeletion()
                                                            ->disableItemMovement()
                                                            ->columnSpan(1),
                                                    ]),

                                                Forms\Components\Section::make('Instrumen Asesmen & Potensi Asesi')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('assessment_instruments')
                                                            ->label('')
                                                            ->schema([
                                                                Forms\Components\Placeholder::make('method_display')
                                                                    ->label('Instrumen Asesmen')
                                                                    ->content(fn ($get) => $get('method'))
                                                                    ->columnSpan(3),
                                                                
                                                                Forms\Components\Radio::make('answer')
                                                                    ->label('Potensi Asesi')
                                                                    ->options([
                                                                        1 => '1',
                                                                        2 => '2',
                                                                        3 => '3',
                                                                        4 => '4',
                                                                        5 => '5',
                                                                    ])
                                                                    ->inline()
                                                                    ->inlineLabel(false)
                                                                    ->columnSpan(2),
                                                            ])
                                                            ->columns(5)
                                                            ->disableItemCreation()
                                                            ->disableItemDeletion()
                                                            ->disableItemMovement()
                                                            ->columnSpanFull(),
                                                    ])->compact(),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn (array $state): ?string => $state['work_group'] ?? null),
                                    ]),

                                // ── Compiler & Validator ───────────────────────────────
                                Forms\Components\Section::make('Penyusun dan Validator')
                                    ->statePath('compiler_and_validator')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Section::make('Penyusun Dokumen')
                                                    ->icon('heroicon-o-pencil-square')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('compiler')
                                                            ->label('')
                                                            ->schema([
                                                                self::assessorSignatureSchema('Penyusun'),
                                                            ])
                                                            ->minItems(2)
                                                            ->maxItems(2)
                                                            ->disableItemCreation()
                                                            ->disableItemDeletion()
                                                            ->disableItemMovement()
                                                            ->default([[], []]),
                                                    ])->columnSpan(1),

                                                Forms\Components\Section::make('Validator Dokumen')
                                                    ->icon('heroicon-o-shield-check')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('validator')
                                                            ->label('')
                                                            ->schema([
                                                                self::assessorSignatureSchema('Validator'),
                                                            ])
                                                            ->minItems(2)
                                                            ->maxItems(2)
                                                            ->disableItemCreation()
                                                            ->disableItemDeletion()
                                                            ->disableItemMovement()
                                                            ->default([[], []]),
                                                    ])->columnSpan(1),
                                            ]),
                                    ]),

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereNotNull('mapa02_data'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Registration ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMapa02s::route('/'),
            'create' => Pages\CreateMapa02::route('/create'),
            'edit' => Pages\EditMapa02::route('/{record}/edit'),
        ];
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    private static function defaultAssessmentInstruments(): array
    {
        return [
            ['method' => 'FR.IA.01. CL - Ceklis Observasi Aktivitas Di Tempat Kerja atau Tempat Kerja Simulasi', 'answer' => null],
            ['method' => 'FR.IA.02. TPD - Tugas Praktik Demonstrasi', 'answer' => null],
            ['method' => 'FR.IA.03. PMO - Pertanyaan Untuk Mendukung Observasi', 'answer' => null],
            ['method' => 'FR.IA.04A. DIT - Daftar Instruksi Terstructured (Penjelasan Singkat Proyek Terkait Pekerjaan/Kegiatan Terstructured Lainnya)', 'answer' => null],
            ['method' => 'FR.IA.04B. DIT – Penilaian Proyek Singkat atau Kegiatan Terstructured Lainnya', 'answer' => null],
            ['method' => 'FR.IA.05. DPT - Daftar Pertanyaan Tertulis Pilihan Ganda', 'answer' => null],
            ['method' => 'FR.IA.06. DPT – Daftar Pertanyaan Tertulis Pilihan Esai', 'answer' => null],
            ['method' => 'FR.IA.07. DPL – Daftar Pertanyaan Lisan', 'answer' => null],
            ['method' => 'FR.IA.08. CVP - Ceklis Verifikasi Portofolio', 'answer' => null],
            ['method' => 'FR.IA.09. PW - Pertanyaan Wawancara', 'answer' => null],
            ['method' => 'FR.IA.10. VPK - Verifikasi Pihak Ketiga', 'answer' => null],
            ['method' => 'FR.IA.11. CRP - Ceklis Reviu Produk', 'answer' => null],
        ];
    }

    private static function assessorSignatureSchema(string $label): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(User::role('Asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('reg_number', $user->registration_number);
                            $set('date', now()->format('d-m-Y'));
                            
                            $sig = UserSignature::where('user_id', $user->id)->first() 
                                ?? UserSignature::where('name', $user->name)->first();
                                
                            if ($sig) {
                                $path = $sig->signature_image;
                                $set('signature', $path ? Storage::url($path) : null);
                            } else {
                                $set('signature', null);
                            }
                        } else {
                            $set('name', null);
                            $set('reg_number', null);
                            $set('signature', null);
                            $set('date', null);
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                
                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(function ($get) {
                        $userId = $get('user_id');
                        if (!$userId) return '-';
                        
                        $user = User::find($userId);
                        if (!$user) return '-';

                        $name = $user->name ?: 'Penyusun/Validator';
                        $regNumber = $user->registration_number ?: 'N/A';
                        
                        // Fix Date: Try getting from state, fallback to today
                        $date = $get('date');
                        if (empty($date)) {
                            $date = now()->format('d-m-Y');
                        }
                        
                        // Look for signature
                        $sig = UserSignature::where('user_id', $userId)->first() 
                            ?? UserSignature::where('name', $name)->first();
                        
                        $signature = null;
                        if ($sig && $sig->signature_image) {
                            $path = $sig->signature_image;
                            if (str_starts_with($path, 'http') || str_starts_with($path, 'data:')) {
                                $signature = $path;
                            } else {
                                $signature = Storage::url($path);
                            }
                        }
                        
                        $img = $signature 
                            ? "<img src='{$signature}' style='max-height: 70px; object-fit: contain; filter: contrast(1.1); z-index: 10;' />" 
                            : "<div class='text-[10px] text-danger-600 font-medium italic'>Tanda tangan belum diunggah</div>";
                        
                        $qrImg = "";
                        if ($sig && $sig->qr_code) {
                            $qrImg = "<img src='{$sig->qr_code}' style='max-height: 70px;' />";
                        }

                        return new HtmlString("
                            <div class='bg-white text-gray-900 p-5 rounded-xl shadow-xl border border-gray-200 mt-2 overflow-hidden' style='font-family: sans-serif; min-width: 360px;'>
                                <div class='flex justify-between items-start border-b border-gray-100 pb-4 mb-4'>
                                    <div>
                                        <div class='text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1'>Nama Lengkap</div>
                                        <div class='text-base font-bold text-black uppercase' style='letter-spacing: 0.5px;'>{$name}</div>
                                        
                                        <div class='mt-4'>
                                            <div class='text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1'>No. Registrasi MET</div>
                                            <div class='text-sm font-semibold text-gray-700'>{$regNumber}</div>
                                        </div>
                                    </div>
                                    <div class='bg-gray-50 p-2 rounded-lg border border-gray-100 shadow-inner'>
                                        {$qrImg}
                                    </div>
                                </div>
                                
                                <div class='flex items-center justify-between gap-8 mt-2'>
                                    <div class='flex-1'>
                                        <div class='text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2'>Tanda Tangan</div>
                                        <div class='relative h-24 w-full flex items-center justify-center bg-gray-50/80 rounded-lg border-2 border-dashed border-gray-200'>
                                            <div class='z-10 relative'>
                                                {$img}
                                            </div>
                                            <!-- Signature Line -->
                                            <div class='absolute bottom-4 left-4 right-4 h-[2px] bg-gray-300 opacity-60' style='border-radius: 1px;'></div>
                                        </div>
                                    </div>
                                    <div class='text-center min-w-[110px]'>
                                        <div class='text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2'>Tanggal</div>
                                        <div class='text-sm font-black text-primary-700 bg-primary-50 px-3 py-2 rounded-lg border-2 border-primary-100 shadow-sm' style='display: block;'>
                                            {$date}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ");
                    })
                    ->visible(fn ($get) => $get('user_id') !== null),
            ]);
    }
}
