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
    protected function getPackageProviders($app)
    {
        return [GroupsServiceProvider::class];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench']);
        $this->withFactories(__DIR__ . '/database/factories');

        // and other test setup steps you need to perform
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

}