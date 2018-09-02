<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 22.07.2018
 * Time: 11:56
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Groups;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Seat\Web\Models\Group;

class SyncGroup
{
    public function execute (Group $group)
    {
        //$this->info('Updating User: ' . $group->main_character->name);
        $roles = collect();
        if($group->main_character_id != "0")
        Seatgroup::all()->each(function ($seat_group) use ($roles, $group)
        {

            //Catch Superuser
            foreach ($group->roles as $role) {
                if ($role->title === "Superuser") {
                    $roles->push($role->id);
                }
            }
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

        return $group;
    }

}