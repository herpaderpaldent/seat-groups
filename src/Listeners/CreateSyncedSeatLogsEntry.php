<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 20:28.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Seat\Web\Models\Acl\Role;

class CreateSyncedSeatLogsEntry implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {

    }

    public function handle(GroupSynced $event)
    {
        if (empty($event->sync['attached']) && empty($event->sync['detached']))
            $this->delete();

        if (! empty($event->sync['attached'])) {

            SeatgroupLog::create([
                'event'   => 'attached',
                'message' => sprintf('The user group of %s (%s) has successfully been attached to the following roles: %s.',
                    $event->main_character->name,
                    $event->group->users->map(function ($user) {

                        return $user->name;
                    })->implode(', '),
                    Role::whereIn('id', $event->sync['attached'])->pluck('title')->implode(', ')
                ),
            ]);
        }

        if (! empty($event->sync['detached'])) {

            SeatgroupLog::create([
                'event'   => 'detached',
                'message' => sprintf('The user group of %s (%s) has been detached from the following roles: %s.',
                    $event->main_character->name,
                    $event->group->users->map(function ($user) {

                        return $user->name;
                    })->implode(', '),
                    Role::whereIn('id', $event->sync['detached'])->pluck('title')->implode(', ')
                ),
            ]);
        }

    }

    /**
     * Handle a job failure.
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced $event
     * @param  \Exception                                         $exception
     *
     * @return void
     */
    public function failed(GroupSynced $event, $exception)
    {
        report($exception);
    }
}
