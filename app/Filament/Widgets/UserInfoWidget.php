<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-info-widget';

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';
}