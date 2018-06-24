<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Console\Command;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Group;

class SeatGroupsUsersUpdate extends Command
{

    use AccessManager;

    protected $signature = 'seat-groups:users:update';

    protected $description = 'This command adds and removes roles to all users depending on their SeAT-Group Association';

    public function __construct()
    {

        parent::__construct();
    }

    public function handle()
    {

        Group::all()->filter(function ($users_group) {

            return $users_group->main_character_id != "0";
        })->each(function ($users_group) {

            $this->info('Updating User: ' . $users_group->main_character->name);
            $roles = collect();
            Seatgroup::all()->each(function ($seat_group) use ($roles, $users_group) {

                //Catch Superusers
                foreach ($users_group->roles as $role) {
                    if ($role->title === "Superuser") {
                        $roles->push($role->id);
                    }
                }
                // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
                if ($seat_group->type == 'auto') {
                    if (in_array($users_group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                        foreach ($seat_group->role as $role) {
                            $roles->push($role->id);
                        }
                    }
                }
                // Opt-In Group Check
                if ($seat_group->type == 'open') {
                    // check if user's corp is allowed in the seatgroup
                    if (in_array($users_group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                        // check if user is Opt-in into a group
                        if (in_array($users_group->id, $seat_group->group->pluck('id')->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                    }
                }
                // Managed Group Check
                if ($seat_group->type == 'managed') {
                    if (in_array($users_group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                        // check if user is member of the managed group
                        if (in_array($users_group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                    }
                }
                // Hidden Group Check
                if ($seat_group->type == 'hidden') {
                    if (in_array($users_group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray()) || $seat_group->all_corporations) {
                        // check if user is member of the hidden group
                        if (in_array($users_group->id, $seat_group->member->map(function ($user) {return $user->id;})->toArray())) {
                            foreach ($seat_group->role as $role) {
                                $roles->push($role->id);
                            }
                        }
                    }
                }
                $users_group->roles()->sync($roles->unique());
            });
        });

    }
}
