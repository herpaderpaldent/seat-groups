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
    }
    private function addCommands()
    {
        $this->commands([
            SeatGroupsUsersUpdate::class,
        ]);
    }
}