<?php

namespace App\Providers;

use App\Models\AssessmentRegistration;
use App\Policies\RegistrationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Perpanjang waktu tunggu PHP untuk database remote yang lambat
        @set_time_limit(300);

        // Optimasi sesi koneksi MySQL untuk database remote
        if (config('database.default') === 'mysql') {
            try {
                DB::statement("SET SESSION wait_timeout = 300");
                DB::statement("SET SESSION interactive_timeout = 300");
            } catch (\Exception $e) {
                // Abaikan jika database belum siap
            }
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });

        Gate::define('view_document', function ($user) {
            return $user->hasRole(['admin', 'superadmin', 'asesor']);
        });

        \App\Models\UserSignature::observe(\App\Observers\UserSignatureObserver::class);

        Table::configureUsing(function (Table $table): void {
            if ($table->getLivewire() instanceof \Filament\Resources\Pages\ListRecords) {
                $table->defaultSort('created_at', 'desc');
            }
        });

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::CONTENT_START,
            function () {
                if (auth()->check() && auth()->user()->hasRole('asesor') && !\App\Models\UserSignature::where('user_id', auth()->id())->exists()) {
                    $route = request()->route()?->getName() ?? '';
                    // Berlaku untuk semua halaman Create dan Edit, KECUALI UserSignatureResource
                    if (str_contains($route, 'filament.admin.resources') && !str_contains($route, 'user-signatures') && (str_contains($route, '.create') || str_contains($route, '.edit'))) {
                        return new \Illuminate\Support\HtmlString('
                            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin-bottom: 20px; border-radius: 8px;">
                                <div style="display: flex; align-items: center;">
                                    <svg style="color: #ef4444; width: 32px; height: 32px; margin-right: 12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <div>
                                        <h3 style="color: #991b1b; font-weight: 600; font-size: 16px; margin: 0;">Akses Terbatas: Tanda Tangan Master Belum Dibuat</h3>
                                        <p style="color: #b91c1c; font-size: 14px; margin: 4px 0 0 0;">Anda belum membuat Tanda Tangan Master Data. Anda harus membuat TTD terlebih dahulu di menu <strong>Mastering Data -> Daftar Tanda Tangan</strong> sebelum Anda diizinkan untuk membuat atau mengedit form asesmen (termasuk memilih dropdown Asesi).</p>
                                    </div>
                                </div>
                            </div>
                            <style>
                                /* Sembunyikan semua form input (dropdown, tombol save, dll) */
                                form, .fi-form, .fi-page-header-actions, .fi-form-actions { display: none !important; }
                            </style>
                        ');
                    }
                }
                return '';
            }
        );
    }
}
