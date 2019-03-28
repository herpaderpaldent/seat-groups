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

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupSync\AbstractSeatGroupSyncNotification;
use Herpaderpaldent\Seat\SeatNotifications\Models\NotificationRecipient;
use Herpaderpaldent\Seat\SeatNotifications\SeatNotificationsServiceProvider;
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

        if (! class_exists(SeatNotificationsServiceProvider::class))
            $should_send = false;

        if ($should_send){

            $recipients = NotificationRecipient::all()
                ->filter(function ($recipient) {
                    return $recipient->shouldReceive(AbstractSeatGroupSyncNotification::class);
                });

            if($recipients->isEmpty()){
                logger()->debug('No Receiver found for ' . AbstractSeatGroupSyncNotification::getTitle() . ' Notification. This job is going to be deleted.');

                return false;
            }

            $recipients->groupBy('driver')
                ->each(function ($grouped_recipients) use ($event) {
                    $driver = (string) $grouped_recipients->first()->driver;
                    $notification_class = AbstractSeatGroupSyncNotification::getDriverImplementation($driver);

                    Notification::send($grouped_recipients, (new $notification_class($event->group, $event->sync)));
                });
        }
    }
}
