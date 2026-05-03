<?php

namespace App\Filament\Resources;

use App\Enums\AppealStatus;
use App\Filament\Resources\Ak04Resource\Pages;
use App\Models\Appeal;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak04Resource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.04';
    protected static ?string $modelLabel = 'AK.04';
    protected static ?string $pluralModelLabel = 'AK.04';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Status Verifikasi')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('ID Pendaftaran')
                                    ->relationship('registration', 'id')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        $candidate = $record->candidate?->name ?? 'Unknown';
                                        $scheme = $record->scheme?->name ?? 'No Scheme';
                                        return "{$candidate} — {$scheme} — #{$record->id}";
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->hint('Hanya pendaftaran yang SUDAH mengajukan banding yang akan muncul di sini.')
                                    ->hintColor('primary')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->disabled()
                                    ->dehydrated(),
                                
                                Forms\Components\Select::make('status')
                                    ->label('Status Banding')
                                    ->options(AppealStatus::class)
                                    ->required()
                                    ->native(false),
                                
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Tanggal Pengajuan')
                                    ->content(fn ($record) => $record?->created_at?->translatedFormat('d F Y H:i') ?? '-'),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Banding (AK.04)')
                    ->statePath('ak04_data')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('asesi_name')
                                    ->label('Nama Asesi')
                                    ->disabled(),
                                Forms\Components\TextInput::make('asesor_name')
                                    ->label('Nama Asesor')
                                    ->disabled(),
                                Forms\Components\TextInput::make('assessment_date')
                                    ->label('Tanggal Asesmen')
                                    ->disabled(),
                                Forms\Components\Group::make([
                                    Forms\Components\TextInput::make('appeal_target.scheme_name')
                                        ->label('Skema Sertifikasi')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('appeal_target.scheme_code')
                                        ->label('Kode Skema')
                                        ->disabled(),
                                ])->columns(2),
                            ]),
                        
                        Forms\Components\Section::make('Pertanyaan Banding')
                            ->schema([
                                Forms\Components\Repeater::make('appeal_questions')
                                    ->label('')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->label('Pertanyaan')
                                            ->disabled()
                                            ->columnSpan(3),
                                        Forms\Components\Toggle::make('answer')
                                            ->label('Jawaban (Ya/Tidak)')
                                            ->disabled()
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark')
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false),
                            ]),

                        Forms\Components\Textarea::make('reason_notes')
                            ->label('Alasan Banding')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Section::make('Tanda Tangan Asesi')
                            ->schema([
                                Forms\Components\Placeholder::make('signature_preview')
                                    ->label('Tanda Tangan')
                                    ->content(function ($get) {
                                        $sig = $get('signature.asesi_signature');
                                        if (!$sig) return 'Belum ada tanda tangan';
                                        
                                        // Usually it's base64 from Ak04Controller logic
                                        return new HtmlString("<img src='{$sig}' style='max-height:150px; border:1px solid #e5e7eb; border-radius:8px;' />");
                                    }),
                                Forms\Components\TextInput::make('signature.date')
                                    ->label('Tanggal Tanda Tangan')
                                    ->disabled(),
                            ])->columns(2),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('ID Daftar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')
                    ->label('Asesi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (AppealStatus $state): string => match ($state) {
                        AppealStatus::PENDING => 'warning',
                        AppealStatus::APPROVED => 'success',
                        AppealStatus::REJECTED => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(AppealStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make()
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageAk04s::route('/'),
        ];
    }
}
