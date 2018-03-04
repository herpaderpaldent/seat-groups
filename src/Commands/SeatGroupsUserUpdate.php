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
use Illuminate\Console\Command;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\User;

class SeatGroupsUsersUpdate extends Command
{
    use AccessManager;

    protected $signature = 'seat-groups:users:update';
    protected $description = 'some description';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {


        $Users = User::all();
        $SeatGroups = Seatgroup::all();

        foreach ($Users as $user){
            $Roles = [];
            $this->info('Updating User: ' . $user->name);

            foreach ($SeatGroups as $seatGroup){
                // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
                if($seatGroup['type'] == 'auto' ){
                    /* Alliance
                    if(SeatGroupAlliance::where('alliance_id', '=',CharacterInfo::find($user->id)['alliance_id'])->count() >0){
                        array_push($Roles,$seatGroup->role['id']);
                    }*/

                    // Corporation
                    elseif (Seatgroupcorporation::where('corporation_id', '=',CharacterInfo::find($user->id)['corporation_id'])->count() >0) {
                        array_push($Roles,$seatGroup->role['id']);
                    }
                }
                /**
                 * As soon as someone is on the Seatgroup_user table he gets synced to the corresponing
                 * Role
                 **/
                if (Seatgroupuser::where('user_id', '=',CharacterInfo::find($user->id)['user_id'])->count() >0){
                    array_push($Roles,$seatGroup->role['id']);
                }
            }

            // Assign Roles to user
            $user->roles()->sync($Roles);
            //$this->info('User now is in Role: ' . print_r($Roles,true));
        }






        /*

        $userList =User::all();
        $corporationList =CharacterInfo::all();
        //$this->info($userList);
        //$this->info($user->id)


        //$userList = CharacterInfo::all();
        //logger()->debug("i do something");

        // This does not work since the order is random i need to look for the entry with the character ID.
        //$this->info($corporationList->where('corporation_id',)corporation_id);
        //$corp->$corporation->coproation();

        foreach ($userList as $user) {
            // this prints out the corporation id of every member
            $this->info(
                "name of user " . $user->name .
                " of corporation " .$corporationList->whereStrict('character_id', $user->id)->first()['corporation_id']
            );
            //$this->info("test2 " . $corporationList->);

        };
*/
        /*
         * First: get collection of roles for 1 role
         * Second: compare to collection of how it should be
         * Third: call ->diff() : https://laravel.com/docs/5.5/collections#method-diff
         * Forth: handle the diff collection
         *
         * Handle: The ones present in the current->delete and the ones not present in current->add
         *
         */
    }
}