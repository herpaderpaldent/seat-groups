<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 20:38.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroupNotification;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupUpdateNotification;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\BaseNotification;
use Illuminate\Support\Facades\Notification;

class GroupSyncedNotification
{
    public function __construct()
    {

    }

    public function handle(GroupSynced $event)
    {
        $should_send = false;

        if (! empty($event->sync['attached']))
            $should_send = true;

        if (! empty($event->sync['detached']))
            $should_send = true;

        if (! class_exists(BaseNotification::class))
            $should_send = false;

        if ($should_send){

            $recipients = SeatGroupNotification::all();

            Notification::send($recipients, (new SeatGroupUpdateNotification($event->group, $event->sync)));
        }
    }
}
