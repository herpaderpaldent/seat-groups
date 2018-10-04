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

}