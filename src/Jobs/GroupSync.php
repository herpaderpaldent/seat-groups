<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 01.09.2018
 * Time: 16:16
 */

namespace Herpaderpaldent\Seat\SeatGroups\Jobs;


use Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingRefreshTokenException;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Illuminate\Support\Facades\Redis;
use Seat\Web\Models\Group;

class GroupSync extends SeatGroupsJobBase
{
    /**
     * @var array
     */
    protected $tags = ['sync'];

    private $group;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * ConversationOrchestrator constructor.
     *
     * @param \Seat\Web\Models\Group $group
     */
    public function __construct(Group $group)
    {

        logger()->debug('Initialising SeAT Group sync for ' . $group->main_character->name);

        $this->group = $group;

        array_push($this->tags, 'main_character_id:' . $group->main_character_id);

    }

    public function handle()
    {
        Redis::funnel('seat-groups:jobs.group_sync_'.$this->group->main_character_id)->limit(1)->then(function ()
        {
            $this->beforeStart();

            try {
                $roles = collect();
                $group = $this->group;

                //Catch Superuser
                foreach ($group->roles as $role) {
                    if ($role->title === "Superuser") {
                        $roles->push($role->id);
                    }
                }

                Seatgroup::all()->each(function ($seat_group) use ($roles, $group) {

                    if ($seat_group->isQualified($group)) {
                        switch ($seat_group->type) {
                            case 'auto':
                                foreach ($seat_group->role as $role) {
                                    $roles->push($role->id);
                                }
                                if(!in_array($group->id,$seat_group->member->pluck('id')->toArray())){
                                    // add user_group to seat_group as member if no member yet.
                                    $seat_group->member()->attach($group->id);
                                }
                                break;
                            case 'open':
                            case 'managed':
                            case 'hidden':
                                // check if user is in the group
                                if ($seat_group->isMember($group)) {
                                    foreach ($seat_group->role as $role) {
                                        $roles->push($role->id);
                                    }
                                }
                                break;
                        }
                    } else if(in_array($group->id,$seat_group->member->pluck('id')->toArray())) {
                        $seat_group->member()->detach($group->id);
                    }
                });

                $group->roles()->sync($roles->unique());

                $this->onFinish();

                logger()->debug('Group has beend synced for '. $this->group->main_character->name);

            } catch (\Throwable $exception) {

                $this->onFail($exception);

            }

        }, function ()
        {
            logger()->warning('A GroupSync job is already running for ' . $this->group->main_character->name . ' Removing the job from the queue.');

            $this->delete();
        });


    }

    public function beforeStart()
    {

        foreach ($this->group->users as $user) {

            // If a RefreshToken is missing
            if (is_null($user->refresh_token)) {
                // take away all roles
                $this->group->roles()->sync([]);
                Seatgroup::all()->each(function ($seatgroup) {
                    $seatgroup->member->detach($this->group->id);
                });

                SeatgroupLog::create([
                    'event' => 'warning',
                    'message' => sprintf('The RefreshToken of %s is missing, therefore user group of %s (%s) loses all permissions.',
                        $user->name, $this->group->main_character->name, $this->group->users->map(function($user) { return $user->name; })->implode(', '))

                ]);

                // throw exception
                throw new MissingRefreshTokenException();
            }
        }
    }

    public function onFail($exception)
    {

        report($exception);

        SeatgroupLog::create([
            'event' => 'error',
            'message' => sprintf('An error occurred while syncing user group of %s (%s). Please check the logs.',
                $this->group->main_character->name, $this->group->users->map(function($user) { return $user->name; })->implode(', '))

        ]);

    }

    public function onFinish()
    {
        SeatgroupLog::create([
            'event' => 'success',
            'message' => sprintf('The user group of %s (%s) has successfully been synced.',
                $this->group->main_character->name, $this->group->users->map(function($user) { return $user->name; })->implode(', '))

        ]);
    }

}