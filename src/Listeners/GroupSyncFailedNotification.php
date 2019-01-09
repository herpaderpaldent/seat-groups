<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 20:38.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroupNotification;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupErrorNotification;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\BaseNotification;
use Illuminate\Support\Facades\Notification;
use Seat\Web\Models\User;

class GroupSyncFailedNotification
{
    public function __construct()
    {

    }

    public function handle(GroupSyncFailed $event)
    {
        $should_send = false;

        if (class_exists(BaseNotification::class))
            $should_send = true;

        if ($should_send){

            $recipients = SeatGroupNotification::all();
            $message = sprintf('An error occurred while syncing user group of %s (%s). Please check the logs.',
                $event->main_character->name,
                $event->group->users->map(function ($user) {return $user->name; })->implode(', ')
            );

            Notification::send($recipients, (new SeatGroupErrorNotification(User::find($event->main_character->character_id), $message)));
        }
    }
}
