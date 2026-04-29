<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSignatureResource\Pages;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserSignatureResource extends Resource
{
    protected static ?string $model = UserSignature::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Daftar Tanda Tangan';
    protected static ?string $modelLabel = 'Tanda Tangan';
    protected static ?string $pluralModelLabel = 'Daftar Tanda Tangan';
    public static function getCreateButtonLabel(): string
    {
        return 'Buat Tanda Tangan';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Buat Tanda Tangan Anda')
                    ->schema([
                        Forms\Components\Select::make('name')
                            ->label('Nama / Identifier')
                            ->options(\App\Models\User::pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->placeholder('Pilih nama pengguna'),

                        Forms\Components\ViewField::make('signature_image')
                            ->view('filament.forms.components.signature-pad')
                            ->label('Gambar Tanda Tangan')
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Hidden::make('signature_shape')
                            ->live()
                            ->dehydrated(true),

                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Placeholder::make('qr_preview')
                                    ->label('Pratinjau QR Code')
                                    ->content(fn ($record) => $record && $record->qr_code 
                                        ? new \Illuminate\Support\HtmlString("<img src='{$record->qr_code}' width='200' />") 
                                        : 'QR Code akan dibuat setelah disimpan.'),
                            ])->visible(fn ($record) => $record !== null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('signature_image')
                    ->label('Tanda Tangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUserSignatures::route('/'),
        ];
    }
}