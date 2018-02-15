<?php

namespace Herpaderpaldent\Seat\SeatGroups;

use Illuminate\Support\ServiceProvider;
use Herpaderpaldent\Seat\SeatGroups\Commands\SeatGroupsUsersUpdate;

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
        $this->addPublications();
        $this->addTranslations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/seatgroups.config.php',
            'seatgroups.config'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
    }

    private function addCommands()
    {
        $this->commands([
            SeatGroupsUsersUpdate::class,
        ]);
    }

    private function addPublications()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
            __DIR__ . '/resources/assets/css/' => public_path('web/css'),
        ]);
    }
    private function addRoutes()
    {
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    private function addViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views/seatgroups', 'seatgroups');
    }
    private function addTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'seatgroups');
    }
}