<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\UserInfoWidget::class,
            \App\Filament\Widgets\AsesiTypeReportWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 1;
    }
}