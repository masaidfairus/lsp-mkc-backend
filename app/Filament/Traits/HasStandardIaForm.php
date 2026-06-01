<?php

namespace App\Filament\Traits;

use App\Models\User;
use Filament\Forms;
use Illuminate\Support\HtmlString;

trait HasStandardIaForm
{
    /**
     * Render status info card setelah memilih user (menggantikan signature preview dari library).
     */
    protected static function renderSignatureStatus($get, string $role): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString(
                '<div class="text-gray-400 italic text-sm py-2">Pilih ' . $role . ' untuk meminta tanda tangan</div>'
            );
        }

        $regNumber = $get('reg_number') ?? 'N/A';

        return new HtmlString("
        <div style='font-family: system-ui, sans-serif; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; max-width: 420px;'>
            <div style='display:flex; align-items:center; gap:12px;'>
                <div style='width:42px;height:42px;background:#dbeafe;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;'>
                    <svg width='20' height='20' fill='none' stroke='#3b82f6' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'/></svg>
                </div>
                <div>
                    <p style='font-size:13px;font-weight:600;color:#0f172a;margin:0;'>{$name}</p>
                    <p style='font-size:11px;color:#64748b;margin:2px 0 0;'>{$role} &middot; Reg: {$regNumber}</p>
                </div>
            </div>
            <div style='margin-top:12px;padding:10px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;display:flex;align-items:flex-start;gap:8px;'>
                <span style='font-size:14px;'>⏳</span>
                <p style='font-size:11px;color:#92400e;margin:0;line-height:1.5;'>Permintaan tanda tangan akan dikirim ke <strong>{$name}</strong> setelah dokumen disimpan. Mereka akan meninjau dan menandatangani melalui menu <em>Permintaan TTD</em>.</p>
            </div>
        </div>
        ");
    }

    /**
     * Standard signature block untuk Asesor.
     * Tidak ada picker manual — asesor otomatis dari data pendaftaran.
     */
    protected static function standardAssessorSignatureBlock(string $statePath): Forms\Components\Section
    {
        // BAGIAN FORM: Grup bidang form berbingkai (Section)
        return Forms\Components\Section::make('Tanda Tangan Asesor')
            ->statePath($statePath)
            ->schema([
                Forms\Components\Hidden::make('user_id'),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),

                Forms\Components\Placeholder::make('assessor_info')
                    ->label('')
                    ->content(fn($get) => $get('name')
                        ? new HtmlString("
                            <div style='font-family:system-ui,sans-serif;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;'>
                                <div style='display:flex;align-items:center;gap:12px;'>
                                    <div style='width:38px;height:38px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;'>
                                        <svg width='18' height='18' fill='none' stroke='#16a34a' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'/></svg>
                                    </div>
                                    <div>
                                        <p style='font-size:13px;font-weight:600;color:#15803d;margin:0;'>{$get('name')}</p>
                                        <p style='font-size:11px;color:#16a34a;margin:2px 0 0;'>Asesor &middot; Reg: {$get('reg_number')}</p>
                                    </div>
                                </div>
                                <div style='margin-top:10px;padding:8px 12px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;font-size:11px;color:#92400e;line-height:1.5;'>
                                    &#9203; Permintaan tanda tangan akan dikirim ke <strong>{$get('name')}</strong> setelah dokumen disimpan.
                                </div>
                            </div>
                        ")
                        : new HtmlString("
                            <div style='background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;padding:14px;color:#94a3b8;font-size:12px;text-align:center;'>
                                Asesor otomatis diisi dari data pendaftaran. Pilih pendaftaran terlebih dahulu.
                            </div>
                        ")),
            ]);
    }

    /**
     * Helper: populate assessor signature from registration's assigned assessor.
     * Call this inside afterStateUpdated / afterStateHydrated of registration_id.
     */
    protected static function populateAssessorSignature(int|string|null $assessorId, callable $set, string $sigPath = 'main_data.signatures.assessor'): void
    {
        if (!$assessorId) return;
        $user = \App\Models\User::find($assessorId);
        if ($user) {
            $set("{$sigPath}.user_id", $user->id);
            $set("{$sigPath}.name", $user->name);
            $set("{$sigPath}.reg_number", $user->registration_number ?? '');
            $set("{$sigPath}.date", now()->format('d-m-Y'));
        }
    }

    /**
     * Standard disclaimer untuk Asesi signature.
     */
    protected static function standardAsesiDisclaimer(): Forms\Components\Section
    {
        // BAGIAN FORM: Grup bidang form berbingkai (Section)
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
     * Mendapatkan opsi dropdown pendaftaran yang sudah difilter.
     *
     * Logika:
     * - Saat CREATE: hanya tampilkan registrasi yang BELUM memiliki dokumen dari tipe form ini.
     * - Saat EDIT: tampilkan registrasi yang belum terpakai + registrasi milik record yang sedang diedit.
     * - Memanfaatkan static::getEloquentQuery() sehingga filter document_type & scope lainnya ikut diterapkan.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $record  Record aktif (null saat create)
     * @return array<int|string, string>  Peta [registration_id => label]
     */
    protected static function getFilteredRegistrationOptions(?\Illuminate\Database\Eloquent\Model $record): array
    {
        // Ambil semua registration_id yang sudah memiliki dokumen jenis ini
        $usedIds = static::getEloquentQuery()->pluck('registration_id')->toArray();

        // Saat mode EDIT: jangan kecualikan registrasi milik record yang sedang diedit
        if ($record && $record->registration_id) {
            $usedIds = array_values(array_filter(
                $usedIds,
                fn ($id) => (int) $id !== (int) $record->registration_id
            ));
        }

        // Ambil semua pendaftaran yang masih tersedia (belum dibuatkan dokumen tipe ini)
        $query = \App\Models\AssessmentRegistration::with(['candidate', 'scheme'])
            ->whereNotIn('id', $usedIds);
            
        // Filter agar asesor hanya melihat pendaftaran yang ditugaskan kepadanya
        if (auth()->check() && auth()->user()->hasRole('asesor')) {
            $query->where('assessor_id', auth()->id());
        }

        $registrations = $query->get();

        $occurrences = [];
        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
            $key               = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
            $candidate         = $reg->candidate?->name ?? 'Unknown';
            $scheme            = $reg->scheme?->name ?? 'Tanpa Skema';
            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
        })->toArray();
    }

    /**
     * Standard Document Control (Penyusun & Validator).
     */
    protected static function standardDocumentControlBlock(string $statePath): Forms\Components\Section
    {
        // BAGIAN FORM: Grup bidang form berbingkai (Section)
        return Forms\Components\Section::make('Kontrol Dokumen')
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
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

                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
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
     * Schema untuk Penyusun/Validator.
     */
    protected static function standardSignatureSchema(string $role): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->schema([
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                Forms\Components\Select::make('user_id')
                    ->label("Pilih {$role}")
                    ->options(User::role(strtolower($role))->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('reg_number', $user->registration_number ?? '');
                            $set('date', now()->format('d-m-Y'));
                        } else {
                            $set('name', null);
                            $set('reg_number', null);
                            $set('date', null);
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),

                Forms\Components\Placeholder::make('sig_status')
                    ->label('')
                    ->content(fn($get) => self::renderSignatureStatus($get, $role)),
            ]);
    }
}
