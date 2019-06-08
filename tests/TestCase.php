<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 03.10.2018
 * Time: 19:27
 */

namespace Herpaderpaldent\Seat\SeatGroups\Test;

use Herpaderpaldent\Seat\SeatGroups\GroupsServiceProvider;
use Herpaderpaldent\Seat\SeatGroups\Test\Stubs\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\ServicesServiceProvider;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    protected $test_user;

    protected $test_character;

    protected $test_corporation;

    protected $group;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        Route::auth();

        // setup database
        $this->setupDatabase($this->app);
        $this->withFactories(__DIR__ . '/database/factories');

        //dd(Schema::getColumnListing('users'));
        $this->test_user = factory(User::class)->create();

        factory(RefreshToken::class)->create([
            'character_id' => $this->test_user->id,
        ]);

        $this->test_character = factory(CharacterInfo::class)->create([
            'character_id' => $this->test_user->id,
            'name' => $this->test_user->name
        ]);

        $this->test_corporation = factory(CorporationInfo::class)->create([
            'corporation_id' => $this->test_user->character->corporation_id
        ]);

        $this->group = Group::find($this->test_user->group_id);

        // add 2 users to test_users user_group.
        factory(User::class, 2)->create([
            'group_id' => $this->test_user->group_id
        ]);

        // PHP Unit Fix for Laravel packages test
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
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
            ServicesServiceProvider::class,
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

        //$app['router']->aliasMiddleware('auth', Authenticate::class);
    }

}