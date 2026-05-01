<?php

namespace App\Providers;

use App\Models\AssessmentRegistration;
use App\Policies\RegistrationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Table;

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
        Gate::before(function ($user, $ability) {
            return ($user->hasRole('super_admin') || $user->hasRole('admin')) ? true : null;
        });

        \App\Models\UserSignature::observe(\App\Observers\UserSignatureObserver::class);

        Table::configureUsing(function (Table $table): void {
            if ($table->getLivewire() instanceof \Filament\Resources\Pages\ListRecords) {
                $table->defaultSort('created_at', 'desc');
            }
        });
    }
}
