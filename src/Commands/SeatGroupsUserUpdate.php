<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroupalliance;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroupcorporation;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroupuser;
use http\Exception;
use Illuminate\Console\Command;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

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

        $Seat_Groups = Seatgroup::all();
        $Users_Groups = Group::all();

        foreach ($Users_Groups as $users_group) {

            if($users_group->main_character_id === "0"){
                continue;
            }

            $this->info('Updating User: ' . $users_group->main_character->name);

            foreach ($Seat_Groups as $seat_group) {

                // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
                if ($seat_group->type == 'auto') {
                    if (in_array($users_group->main_character->corporation_id, $seat_group->corporation->pluck('corporation_id')->toArray())) {
                        foreach ($seat_group->role as $role){
                            $this->giveGroupRole($users_group->id,$role->id);
                        }
                    } elseif (!in_array($users_group->main_character->corporation_id,$seat_group->corporation->pluck('corporation_id')->toArray())){
                        foreach ($seat_group->role as $role){
                            $this->removeGroupFromRole($users_group->id,$role->id);
                        }
                    }
                }

            }

            // Opt-In Group Check
            if ($seat_group->type == 'open'){
                // check if user's corp is allowed in the seatgroup
                if (in_array($users_group->main_character->corporation_id,$seat_group->corporation->pluck('corporation_id')->toArray())){
                    // check if user is Opt-in into a group
                    if(in_array($users_group->id , $seat_group->group->pluck('id')->toArray())){
                        foreach ($seat_group->role as $role){
                            $this->giveGroupRole($users_group->id,$role->id);
                        }
                    }
                } elseif (!in_array($users_group->main_character->corporation_id,$seat_group->corporation->pluck('corporation_id')->toArray())){
                    foreach ($seat_group->role as $role){
                        $this->removeGroupFromRole($users_group->id,$role->id);
                    }
                }
            }

        }
    }
}
