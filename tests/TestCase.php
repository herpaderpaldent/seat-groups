<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 03.10.2018
 * Time: 19:27
 */

namespace Herpaderpaldent\Seat\SeatGroups\Test;

use Herpaderpaldent\Seat\SeatGroups\GroupsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Seat\Eveapi\EveapiServiceProvider;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

abstract class TestCase extends OrchestraTestCase
{

    protected $test_user;

    protected $group;
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        // setup database
        $this->setupDatabase($this->app);
        $this->withFactories(__DIR__ . '/database/factories');

        $this->test_user = factory(User::class)->create();

        factory(CharacterInfo::class)->create([
            'character_id' => $this->test_user->id,
            'name' => $this->test_user->name
        ]);

        factory(RefreshToken::class)->create([
            'character_id' => $this->test_user->id,
        ]);

        $this->group = Group::find($this->test_user->group_id);

        // add 2 users to test_users user_group.
        factory(User::class, 2)->create([
            'group_id' => $this->test_user->group_id
        ]);
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
            WebServiceProvider::class,
            //EveapiServiceProvider::class,
            GroupsServiceProvider::class,
        ];
    }

    protected function setupDatabase($app)
    {
        // Path to our migrations to load
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench']);


    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Use memory SQLite, cleans it self up
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

}