<?php

namespace Herpaderpaldent\Seat\SeatGroups;

use Herpaderpaldent\Seat\SeatGroups\Observers\RefreshTokenObserver;
use Illuminate\Support\ServiceProvider;
use Seat\Eveapi\Models\RefreshToken;

class GroupsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->addCommands();
        $this->addRoutes();
        $this->addViews();
        $this->addTranslations();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        RefreshToken::observe(RefreshTokenObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/seatgroups.permission.php', 'web.permissions');

        $this->mergeConfigFrom(
            __DIR__ . '/config/seatgroups.config.php', 'seatgroups.config'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/config/seatgroups.sidebar.php', 'package.sidebar');
    }

    private function addCommands()
    {
        $this->commands([
            Commands\SeatGroupsUsersUpdate::class,
        ]);
    }

    private function addRoutes()
    {
        if (! $this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    private function addViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'seatgroups');
    }

    private function addTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'seatgroups');
    }
}
