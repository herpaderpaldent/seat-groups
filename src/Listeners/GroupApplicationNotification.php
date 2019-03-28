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

use Herpaderpaldent\Seat\SeatGroups\Events\GroupApplication;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupApplication\AbstractSeatGroupApplicationNotification;
use Herpaderpaldent\Seat\SeatNotifications\Models\NotificationRecipient;
use Herpaderpaldent\Seat\SeatNotifications\SeatNotificationsServiceProvider;
use Illuminate\Support\Facades\Notification;
use Seat\Web\Models\Group;

class GroupApplicationNotification
{
    public function __construct()
    {

    }

    public function handle(GroupApplication $event)
    {
        $should_send = false;

        if (class_exists(SeatNotificationsServiceProvider::class))
            $should_send = true;

        if ($should_send){

            $recipients = NotificationRecipient::all()
                ->filter(function ($recipient) {
                    return $recipient->shouldReceive(AbstractSeatGroupApplicationNotification::class);
                })
                ->filter(function ($recipient) {

                    //Filter public subscription as only private subscription is allowed
                    return !empty($recipient->group_id);
                })
                ->filter(function ($recipient) use ($event) {

                    $recipient_group = Group::find($recipient->group_id);

                    // Check if recipient is superuser
                    foreach ($recipient_group->roles as $role) {
                        foreach ($role->permissions as $permission) {
                            if ($permission->title === 'superuser')
                                return true;
                        }
                    }

                    // Check if recipient is manager
                    return $event->seatgroup->isManager($recipient_group);
                });

            if($recipients->isEmpty()){
                logger()->debug('No Receiver found for ' . AbstractSeatGroupApplicationNotification::getTitle() . ' Notification. This job is going to be deleted.');

                return false;
            }

            $recipients->groupBy('driver')
                ->each(function ($grouped_recipients) use ($event) {
                    $driver = (string) $grouped_recipients->first()->driver;
                    $notification_class = AbstractSeatGroupApplicationNotification::getDriverImplementation($driver);

                    Notification::send($grouped_recipients, (new $notification_class($event->seatgroup, $event->group)));
                });
        }
    }
}
