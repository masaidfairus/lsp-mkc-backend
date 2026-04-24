<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSignatureResource\Pages;
use App\Filament\Resources\UserSignatureResource\RelationManagers;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSignatureResource extends Resource
{
    protected static ?string $model = \App\Models\UserSignature::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Signature Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Create Your Signature')
                    ->schema([
                        Forms\Components\Select::make('name')
                            ->label('Name/Identifier')
                            ->options(\App\Models\User::pluck('name', 'name'))
                            ->searchable()
                            ->required()
                            ->placeholder('Select user name'),
                        
                        Forms\Components\ViewField::make('signature_image')
                            ->view('filament.forms.components.signature-pad')
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\Hidden::make('signature_shape')
                            ->live()
                            ->dehydrated(true),

                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Placeholder::make('qr_preview')
                                    ->label('QR Code Preview')
                                    ->content(fn ($record) => $record && $record->qr_code ? new \Illuminate\Support\HtmlString("<img src='{$record->qr_code}' width='200' />") : 'QR will be generated after saving.'),
                            ])->visible(fn ($record) => $record !== null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('signature_image')
                    ->label('Signature'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageUserSignatures::route('/'),
        ];
    }
}
