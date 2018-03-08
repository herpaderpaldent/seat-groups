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


        $Users = User::all();
        $SeatGroups = Seatgroup::all();

        foreach ($Users as $user) {
            $Roles = [];
            $this->info('Updating User: ' . $user->name);

            foreach ($SeatGroups as $seatGroup) {
                // AutoGroup: ppl in the alliance or corporation of a autogroup, are getting synced.
                $helper2 = $seatGroup->corporation()->pluck('corporation_id')->toArray();
                $this->info('Updating User: ' . $helper2);
                if ($seatGroup['type'] == 'auto') {
                    $helper1 = $user->id;

                    if (in_array($helper1, $helper2)) {
                        array_push($Roles, $seatGroup->role['id']);
                    }
                }

                // Assign Roles to user
                $user->roles()->sync($Roles);
                //$this->info('User now is in Role: ' . print_r($Roles,true));
            }
        }
    }
}
