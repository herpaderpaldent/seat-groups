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
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\User;

class SeatGroupsUsersUpdate extends Command
{

    use AccessManager, Corporation;

    protected $signature = 'seat-groups:users:update';
    protected $description = 'some description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $SeatGroups = Seatgroup::all();



        $Users = User::all();

        foreach ($Users as $user) {
            $Roles = [];
            $this->info('Updating User: ' . $user->name);

            foreach ($SeatGroups as $seatGroup) {

                // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
                if ($seatGroup->type == 'auto') {
                    $corporations = $seatGroup->corporation->pluck('corporation_id')->toArray();

                    if (in_array(CharacterInfo::find($user->id)->corporation_id,$corporations)) {
                        array_push($Roles, $seatGroup->role_id);
                    }
                }

                // Opt-In Group Check
                // check if user's corp is allowed in the seatgroup
                if (count($seatGroup->corporation->firstwhere('corporation_id','=',$user->character->corporation_id))>0){
                    // check if user is Opt-in into a group
                    if($seatGroup->isMember($user->getAuthIdentifier(),$seatGroup->id)){
                        array_push($Roles, $seatGroup->role_id);
                    }

                }
                // Assign Roles to user & using unique role
                $user->roles()->sync(array_unique($Roles));

            }
        }
    }
}
