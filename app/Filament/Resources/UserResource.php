<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 3;

    /**
     * Form.
     *
     * @param  Form  $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (Page $livewire) => ($livewire instanceof CreateRecord))
                            ->maxLength(255),
                    ])->columns(2),

                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('Identitas & Peran')
                    ->description('Opsional: Isi informasi identitas jika diperlukan.')
                    ->schema([
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('identity_type')
                            ->options([
                                'KTP' => 'KTP',
                                'SIM' => 'SIM',
                                'Paspor' => 'Paspor',
                            ])
                            ->nullable(),
                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        Forms\Components\TextInput::make('identity_number')
                            ->maxLength(20)
                            ->nullable(),
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->required(),
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('active'),
                    ])->columns(2),
            ]);
    }

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->label('Roles')
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'asesor' => 'success',
                        'penyusun' => 'info',
                        'validator' => 'primary',
                        default => 'gray',
                    }),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('identity_number')
                    ->label('ID Number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    /**
     * Mendapatkan relasi getRelations.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
