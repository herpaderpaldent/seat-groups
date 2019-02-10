<?php
/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Herpaderpaldent\Seat\SeatGroups;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupApplication;
use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Listeners\CreateSyncedSeatLogsEntry;
use Herpaderpaldent\Seat\SeatGroups\Listeners\CreateSyncFailedLogsEntry;
use Herpaderpaldent\Seat\SeatGroups\Listeners\GroupApplicationNotification;
use Herpaderpaldent\Seat\SeatGroups\Listeners\GroupSyncedNotification;
use Herpaderpaldent\Seat\SeatGroups\Listeners\GroupSyncFailedNotification;
use Herpaderpaldent\Seat\SeatGroups\Listeners\MissingRefreshTokenLogsEntry;
use Herpaderpaldent\Seat\SeatGroups\Listeners\MissingRefreshTokenNotification;
use Herpaderpaldent\Seat\SeatGroups\Observers\RefreshTokenObserver;
use Illuminate\Support\Arr;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\AbstractSeatPlugin;

class GroupsServiceProvider extends AbstractSeatPlugin
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

        $this->add_events();

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

        $this->mergeConfigFrom(
            __DIR__ . '/config/seatgroups.services.php', 'services');

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

    private function add_events()
    {
        $this->app->events->listen(GroupSynced::class, CreateSyncedSeatLogsEntry::class);
        $this->app->events->listen(GroupSynced::class, GroupSyncedNotification::class);

        $this->app->events->listen(GroupSyncFailed::class, CreateSyncFailedLogsEntry::class);
        $this->app->events->listen(GroupSyncFailed::class, GroupSyncFailedNotification::class);

        $this->app->events->listen(MissingRefreshToken::class, MissingRefreshTokenLogsEntry::class);
        $this->app->events->listen(MissingRefreshToken::class, MissingRefreshTokenNotification::class);

        $this->app->events->listen(GroupApplication::class, GroupApplicationNotification::class);
    }

    /**
     * Merge the given configuration with the existing configuration.
     * https://medium.com/@koenhoeijmakers/properly-merging-configs-in-laravel-packages-a4209701746d.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);
        $this->app['config']->set($key, $this->mergeConfigs(require $path, $config));
    }

    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     * https://medium.com/@koenhoeijmakers/properly-merging-configs-in-laravel-packages-a4209701746d.
     *
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    protected function mergeConfigs(array $original, array $merging)
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (! is_array($value)) {
                continue;
            }

            if (! Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfigs($value, $merging[$key]);
        }

        return $array;
    }

    /**
     * Return an URI to a CHANGELOG.md file or an API path which will be providing changelog history.
     *
     * @return string|null
     */
    public function getChangelogUri(): ?string
    {

        return 'https://raw.githubusercontent.com/herpaderpaldent/seat-groups/master/CHANGELOG.md';
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     */
    public function getName(): string
    {

        return 'seat-groups';
    }

    /**
     * Return the plugin repository address.
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {

        return 'https://github.com/herpaderpaldent/seat-groups';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {

        return 'seat-groups';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {

        return 'herpaderpaldent';
    }

    /**
     * Return the plugin installed version.
     *
     * @return string
     */
    public function getVersion(): string
    {

        return config('seatgroups.config.version');
    }
}
