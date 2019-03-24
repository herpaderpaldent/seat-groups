<?php
/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Created by PhpStorm.
 * User: felix
 * Date: 09.01.2019
 * Time: 19:23.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Notifications\MissingRefreshToken\AbstractMissingRefreshTokenNotification;
use Herpaderpaldent\Seat\SeatNotifications\Models\NotificationRecipient;
use Herpaderpaldent\Seat\SeatNotifications\SeatNotificationsServiceProvider;
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

        if (class_exists(SeatNotificationsServiceProvider::class))
            $should_send = true;

        if ($should_send){

            $recipients = NotificationRecipient::all()
                ->filter(function ($recipient) {
                    return $recipient->shouldReceive(AbstractMissingRefreshTokenNotification::class);
                });

            if($recipients->isEmpty()){
                logger()->debug('No Receiver found for ' . AbstractMissingRefreshTokenNotification::getTitle() . ' Notification. This job is going to be deleted.');
                $this->delete();
            }

            $recipients->groupBy('driver')
                ->each(function ($grouped_recipients) use ($event) {
                    $driver = (string) $grouped_recipients->first()->driver;
                    $notification_class = AbstractMissingRefreshTokenNotification::getDriverImplementation($driver);

                    Notification::send($grouped_recipients, (new $notification_class($event->user)));
                });
        }
    }
}
