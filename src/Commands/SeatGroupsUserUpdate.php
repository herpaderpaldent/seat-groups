<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Illuminate\Console\Command;
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
        $userList = User::all();

        foreach ($userList as $user) {
            //echo $user;
            //logger()->debug('bindingSlackUser');
        }
    }
}