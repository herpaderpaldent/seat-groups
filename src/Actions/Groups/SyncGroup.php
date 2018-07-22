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
        Seatgroup::all()->each(function ($seat_group) use ($roles, $group) {

            //Catch Superuser
            foreach ($group->roles as $role) {
                if ($role->title === "Superuser") {
                    $roles->push($role->id);
                }
            }
            // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
            if ($seat_group->type == 'auto') {
                if (in_array($group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                    foreach ($seat_group->role as $role) {
                        $roles->push($role->id);
                    }
                }
            }
            // Opt-In Group Check
            if ($seat_group->type == 'open') {
                // check if user's corp is allowed in the seatgroup
                if (in_array($group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                    // check if user is Opt-in into a group
                    if (in_array($group->id, $seat_group->group->pluck('id')->toArray())) {
                        foreach ($seat_group->role as $role) {
                            $roles->push($role->id);
                        }
                    }
                }
            }
            // Managed Group Check
            if ($seat_group->type == 'managed') {
                if (in_array($group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                    // check if user is member of the managed group
                    if (in_array($group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                        foreach ($seat_group->role as $role) {
                            $roles->push($role->id);
                        }
                    }
                }
            }
            // Hidden Group Check
            if ($seat_group->type == 'hidden') {
                if (in_array($group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                    // check if user is member of the hidden group
                    if (in_array($group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                        foreach ($seat_group->role as $role) {
                            $roles->push($role->id);
                        }
                    }
                }
            }
        });

        $group->roles()->sync($roles->unique());

        return $group;
    }

}