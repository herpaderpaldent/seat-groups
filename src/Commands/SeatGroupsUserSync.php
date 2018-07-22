<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Herpaderpaldent\Seat\SeatGroups\Actions\Groups\SyncGroup;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Console\Command;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

class SeatGroupsUserSync extends Command
{

    use AccessManager;

    protected $signature = 'seat-groups:user:sync {character_id}';

    protected $description = 'This command adds and removes roles to specific users depending on their SeAT-Group Association';

    public function __construct()
    {

        parent::__construct();
    }

    public function handle(SyncGroup $action)
    {

        $this->info($action->execute(User::find($this->argument('character_id'))->group));

        //$this->info($group);

    }
}
