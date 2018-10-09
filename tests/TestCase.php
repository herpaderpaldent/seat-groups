<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 03.10.2018
 * Time: 19:27
 */

namespace Herpaderpaldent\Seat\SeatGroups\Test;

use Herpaderpaldent\Seat\SeatGroups\GroupsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        /*$this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/database/migrations')
            ]);*/
        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->withFactories(__DIR__ . '/database/factories');

        // and other test setup steps you need to perform
    }


    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            GroupsServiceProvider::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        //TODO: find a better way or use dedicated DB
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'mysql',
            'host' => env('DB_HOST', 'mariadb'),
            'port'        => env('DB_PORT', '3306'),
            'database'    => env('DB_DATABASE', 'seat-dev'),
            'username'    => env('DB_USERNAME', 'seat'),
            'password'    => env('DB_PASSWORD', 'seatseat'),
            'prefix'   => '',
        ]);
    }

}