<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;


use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupDispatcher;
use Illuminate\Console\Command;

class SeatGroupsUserSync extends Command
{

    protected $signature = 'seat-groups:user:sync';

    protected $description = 'This command adds and removes roles from groups depending on their SeAT-Group Association';



    public function handle()
    {
        GroupDispatcher::dispatch();
        $this->info('A synchronization job has been queued in order to update SeAT Group roles.');

    }
}
