<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 09.01.2019
 * Time: 19:23.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroupNotification;
use Herpaderpaldent\Seat\SeatGroups\Notifications\MissingRefreshTokenNotification as RefreshTokenNotification;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\BaseNotification;
use Illuminate\Support\Facades\Notification;

class MissingRefreshTokenNotification
{
    public function __construct()
    {

    }

    public function handle(MissingRefreshToken $event)
    {
        $should_send = false;
        logger()->debug('notificationListener');

        if (class_exists(BaseNotification::class))
            $should_send = true;

        if ($should_send){

            $recipients = SeatGroupNotification::all()
                ->filter(function ($recipient) {
                    return $recipient->shouldReceive('missing_refreshtoken');
                });

            Notification::send($recipients, (new RefreshTokenNotification($event->user)));
        }
    }
}
