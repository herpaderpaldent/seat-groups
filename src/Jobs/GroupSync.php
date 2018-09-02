<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 01.09.2018
 * Time: 16:16
 */

namespace Herpaderpaldent\Seat\SeatGroups\Jobs;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
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
    public $tries = 100;

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
        $roles = collect();
        $group = $this->group;

        //Catch Superuser
        foreach ($group->roles as $role) {
            if ($role->title === "Superuser") {
                $roles->push($role->id);
            }
        }

        Seatgroup::all()->each(function ($seat_group) use ($roles, $group)
        {

            if (in_array($group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations)
            {
                switch ($seat_group->type)
                {
                    case 'auto':
                        foreach ($seat_group->role as $role) {
                            $roles->push($role->id);
                        }
                        break;
                    case 'open':
                        // check if user is Opt-in into a group
                        if (in_array($group->id, $seat_group->group->pluck('id')->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                        break;
                    case 'managed':
                        // check if user is member of the managed group
                        if (in_array($group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                        break;
                    case 'hidden':
                        // check if user is member of the hidden group
                        if (in_array($group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                        break;
                }
            }

        });

        $group->roles()->sync($roles->unique());
    }

}