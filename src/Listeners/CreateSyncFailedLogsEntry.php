<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 22:23
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;


use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSyncFailedLogsEntry implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(GroupSyncFailed $event)
    {

        $message = sprintf('An error occurred while syncing user group of %s (%s). Please check the logs.',
            $event->main_character->name,
            $event->group->users->map(function ($user) {return $user->name; })->implode(', ')
        );

        SeatgroupLog::create([
            'event'   => 'error',
            'message' => $message,
        ]);

    }

}