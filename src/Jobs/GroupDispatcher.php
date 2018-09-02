<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 01.09.2018
 * Time: 13:50
 */

namespace Herpaderpaldent\Seat\SeatGroups\Jobs;


use Seat\Web\Models\Group;
use Illuminate\Support\Facades\Redis;

class GroupDispatcher extends SeatGroupsJobBase
{

    /**
     * @var array
     */
    protected $tags = ['dispatcher'];

    public function handle()
    {
        Redis::funnel('seat-groups:jobs.group_dispatcher')->limit(1)->then(function ()
        {

            Group::all()->filter(function ($users_group) {

                return $users_group->main_character_id != "0";
            })->each(function ($users_group)
            {
               $job = new GroupSync($users_group);

               dispatch($job);
            });

        }, function ()
        {
            logger()->warning('A GroupDispatcher job is already running. Remove the job from the queue.');

            $this->delete();
        });
    }

}