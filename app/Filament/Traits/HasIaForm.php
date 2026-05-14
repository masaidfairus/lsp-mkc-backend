<?php

namespace App\Filament\Resources\Traits;

use App\Models\AssessmentRegistration;
use App\Models\User;
use App\Models\UserSignature;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

trait HasIaForm
{
    protected static function registrationSelect(): Forms\Components\Select
    {
        return Forms\Components\Select::make('registration_id')
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
                    $reg = AssessmentRegistration::with(['scheme.competencyUnits.workGroup', 'candidate', 'assessor'])->find($state);
                    if ($reg) {
                        $set('main_data.header.scheme_name', $reg->scheme?->name);
                        $set('main_data.header.scheme_code', $reg->scheme?->code);
                        $set('main_data.header.asesi_name', $reg->candidate?->name);
                        $set('main_data.header.asesor_name', $reg->assessor?->name);
                        $set('main_data.header.tuk', $reg->tuk ?? '');
                        $set('main_data.header.tanggal', now()->format('d-m-Y'));
                    }
                }
            });
    }

    protected static function assessorSignatureBlock(string $key, string $label): Forms\Components\Section
    {
        return Forms\Components\Section::make($label)
            ->statePath("validation_data.signatures.{$key}")
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label("Pilih {$label}")
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
                    ->content(fn ($get) => self::renderSignaturePreview($get)),
            ]);
    }

    protected static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http')) return $path;
        $normalized = preg_replace('#^/?storage/#', '', $path);
        return Storage::url($normalized);
    }

    protected static function renderSignaturePreview($get): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih Asesor untuk melihat preview tanda tangan</div>');
        }

        $regNumber = $get('reg_number') ?? 'N/A';
        $date = $get('date') ?? now()->format('d-m-Y');
        $signature = $get('signature');
        $qrCode = $get('qr_code');

        $imgHtml = $signature
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />"
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Tanda tangan belum diunggah</div>";

        $qrHtml = $qrCode
            ? "<img src='" . htmlspecialchars($qrCode) . "' style='width:70px;height:70px;object-fit:contain;' />"
            : '';

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>Tanda Tangan Asesor</span>
            </div>
            <div style='padding: 20px;'>
                <!-- ... (same beautiful card as before) ... -->
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
}