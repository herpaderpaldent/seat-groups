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

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupError\AbstractSeatGroupErrorNotification;
use Herpaderpaldent\Seat\SeatNotifications\Models\NotificationRecipient;
use Herpaderpaldent\Seat\SeatNotifications\SeatNotificationsServiceProvider;
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

        if (class_exists(SeatNotificationsServiceProvider::class))
            $should_send = true;

        if ($should_send){

            $recipients = NotificationRecipient::all()
                ->filter(function ($recipient) {
                    return $recipient->shouldReceive(AbstractSeatGroupErrorNotification::class);
                });

            if($recipients->isEmpty()){
                logger()->debug('No Receiver found for ' . AbstractSeatGroupErrorNotification::getTitle() . ' Notification. This job is going to be deleted.');
                $this->delete();
            }

            $recipients->groupBy('driver')
                ->each(function ($grouped_recipients) use ($event) {
                    $driver = (string) $grouped_recipients->first()->driver;
                    $notification_class = AbstractSeatGroupErrorNotification::getDriverImplementation($driver);

                    $message = sprintf('An error occurred while syncing user group of %s (%s). Please check the logs.',
                        $event->main_character->name,
                        $event->group->users->map(function ($user) {return $user->name; })->implode(', ')
                    );

                    Notification::send($grouped_recipients, (new $notification_class(User::find($event->main_character->character_id), $message)));
                });
        }
    }
}
