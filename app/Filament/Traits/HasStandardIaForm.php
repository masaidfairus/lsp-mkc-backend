<?php

namespace App\Filament\Traits;

use App\Models\User;
use App\Models\UserSignature;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

trait HasStandardIaForm
{
    /**
     * Resolve the signature URL for the preview.
     */
    protected static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http')) return $path;
        $normalized = preg_replace('#^/?storage/#', '', $path);
        return Storage::url($normalized);
    }

    /**
     * Render a premium signature preview with QR code and MET number.
     */
    protected static function renderSignaturePreview($get, string $role): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih ' . $role . ' untuk melihat preview</div>');
        }

        $regNumber = $get('reg_number') ?? 'N/A';
        $date = $get('date') ?? now()->format('d-m-Y');
        $signature = $get('signature');
        $qrCode = $get('qr_code');

        $imgHtml = $signature
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />"
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Tanda tangan belum diunggah</div>";

        $qrHtml = $qrCode
            ? "<img src='" . htmlspecialchars($qrCode) . "' style='width:70px;height:80px;object-fit:contain;' />"
            : '';

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>{$role}</span>
            </div>
            <div style='padding: 20px;'>
                <div style='display: flex; gap: 16px; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;'>
                    <div style='flex: 1;'>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 4px;'>Nama Lengkap</p>
                        <p style='font-size: 16px; font-weight: 600; margin: 0 0 14px;'>{$name}</p>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 6px;'>No. Registrasi MET</p>
                        <span style='font-size: 13px; font-weight: 500; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 12px; font-family: monospace;'>{$regNumber}</span>
                    </div>
                    <div style='width: 80px; height: 80px; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8fafc; flex-shrink: 0;'>
                        {$qrHtml}
                    </div>
                </div>
                <div style='display: grid; grid-template-columns: 1fr 130px; gap: 16px;'>
                    <div>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;'>Tanda Tangan</p>
                        <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative;'>
                            <div style='position: absolute; bottom: 18px; left: 20px; right: 20px; height: 1px; background: #e2e8f0;'></div>
                            <div style='position: relative; z-index: 10;'>{$imgHtml}</div>
                        </div>
                    </div>
                    <div>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; text-align: center;'>Tanggal</p>
                        <div style='height: 110px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;'>
                            <p style='font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em;'>Ditetapkan</p>
                            <p style='font-size: 15px; font-weight: 600; font-family: monospace;'>{$date}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>";

        return new HtmlString($html);
    }

    /**
     * Standard signature block for Asesor.
     */
    protected static function standardAssessorSignatureBlock(string $statePath): Forms\Components\Section
    {
        return Forms\Components\Section::make('Tanda Tangan Asesor')
            ->statePath($statePath)
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
                            $set('date', now()->format('d-m-Y'));

                            $sig = UserSignature::where('user_id', $user->id)->first()
                                ?? UserSignature::where('name', $user->name)->first();

                            if ($sig) {
                                $set('signature', self::resolveSignatureUrl($sig->signature_image));
                                $set('qr_code', self::resolveSignatureUrl($sig->qr_code));
                            } else {
                                $set('signature', null);
                                $set('qr_code', null);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Hidden::make('qr_code'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
            ]);
    }

    /**
     * Standard disclaimer for Asesi signature.
     */
    protected static function standardAsesiDisclaimer(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Tanda Tangan Asesi')
            ->schema([
                Forms\Components\Placeholder::make('asesi_disclaimer')
                    ->label('')
                    ->content(new HtmlString('
                        <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800">
                            <div class="p-2 bg-amber-100 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Informasi Tanda Tangan Asesi</p>
                                <p class="text-xs opacity-90 leading-relaxed mt-1">Tanda tangan asesi akan secara otomatis tersemat melalui aplikasi mobile setelah proses asesmen selesai. Anda tidak perlu menginputnya secara manual di sini.</p>
                            </div>
                        </div>
                    ')),
            ]);
    }

    /**
     * Standard Document Control (Compilers & Validators).
     */
    protected static function standardDocumentControlBlock(string $statePath): Forms\Components\Section
    {
        return Forms\Components\Section::make('Kontrol Dokumen')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Section::make('Penyusun Dokumen')
                            ->schema([
                                Forms\Components\Repeater::make('compilers')
                                    ->label('')
                                    ->statePath('compilers')
                                    ->schema([self::standardSignatureSchema('Penyusun')])
                                    ->minItems(2)
                                    ->maxItems(2)
                                    ->default([[], []])
                                    ->disableItemCreation()
                                    ->disableItemDeletion()
                                    ->disableItemMovement()
                            ])->columnSpan(1),

                        Forms\Components\Section::make('Validator Dokumen')
                            ->schema([
                                Forms\Components\Repeater::make('validators')
                                    ->label('')
                                    ->statePath('validators')
                                    ->schema([self::standardSignatureSchema('Validator')])
                                    ->minItems(2)
                                    ->maxItems(2)
                                    ->default([[], []])
                                    ->disableItemCreation()
                                    ->disableItemDeletion()
                                    ->disableItemMovement()
                            ])->columnSpan(1),
                    ])
            ])->statePath($statePath);
    }

    /**
     * Schema for Compiler/Validator signatures.
     */
    protected static function standardSignatureSchema(string $role): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label("Pilih {$role}")
                    ->options(User::role('asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('reg_number', $user->registration_number ?? '');
                            $set('date', now()->format('d-m-Y'));

                            $sig = UserSignature::where('user_id', $user->id)->first()
                                ?? UserSignature::where('name', $user->name)->first();

                            if ($sig) {
                                $set('signature', self::resolveSignatureUrl($sig->signature_image));
                                $set('qr_code', self::resolveSignatureUrl($sig->qr_code));
                            } else {
                                $set('signature', null);
                                $set('qr_code', null);
                            }
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Hidden::make('qr_code'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, $role)),
            ]);
    }
}
