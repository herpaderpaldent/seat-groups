<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 06.02.2018
 * Time: 23:22.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Commands;

use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupDispatcher;
use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupSync;
use Illuminate\Console\Command;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

class SeatGroupsUsersUpdate extends Command
{

    protected $signature = 'seat-groups:users:update {--character_ids= : The id list of characters in SeAT (using , as separator)}';

    protected $description = 'Fire a job which attempts to add and remove roles to all user groups depending on their SeAT-Group Association';

    public function handle()
    {

        if(! is_null($this->option('character_ids'))) {
            // transform the argument list in an array
            $ids = explode(',', $this->option('character_ids'));
            $group_ids = collect();

            User::whereIn('id', $ids)->each(function ($user) use ($group_ids) {
                $group_ids->push($user->group->id);
            });

            Group::whereIn('id', $group_ids->unique())->get()
                ->filter(function ($users_group) {
                    return $users_group->main_character_id != '0';
                })
                ->each(function ($group) {
                    dispatch(new GroupSync($group));
                    $this->info(sprintf('A synchronization job has been queued in order to update %s (%s) roles.', $group->main_character->name,
                        $group->users->map(function ($user) { return $user->name; })->implode(', ')));
            });

        } else {
            GroupDispatcher::dispatch();
            $this->info('A synchronization job has been queued in order to update all SeAT Group roles.');
        }

    }
}
