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

        \App\Models\UserSignature::observe(\App\Observers\UserSignatureObserver::class);

        Table::configureUsing(function (Table $table): void {
            if ($table->getLivewire() instanceof \Filament\Resources\Pages\ListRecords) {
                $table->defaultSort('created_at', 'desc');
            }
        });
    }
}
