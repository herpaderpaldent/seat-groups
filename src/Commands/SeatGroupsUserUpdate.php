<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Illuminate\Console\Command;
use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\AffiliationUser;
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
        $userList =User::all();
        $corporationList =CharacterInfo::all();
        //$this->info($userList);


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
        };

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