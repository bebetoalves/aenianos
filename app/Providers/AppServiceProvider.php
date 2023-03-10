<?php

namespace App\Providers;

use App\Filament\Pages\Profile;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

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
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');

            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label(__('pages.profile.title'))
                    ->url(Profile::getUrl())
                    ->icon('heroicon-s-user'),
            ]);
        });
    }
}
