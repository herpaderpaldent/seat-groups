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

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications;

use Herpaderpaldent\Seat\SeatNotifications\Http\Controllers\BaseNotificationController;

class SeatGroupApplicationController extends BaseNotificationController
{
    public function getNotification() : string
    {
        return 'seatgroups::notification.seatgroup_application.notification';
    }

    public function getPrivateView() : string
    {
        return 'seatgroups::notification.seatgroup_application.private';
    }

    public function getChannelView() : string
    {

        return 'seatgroups::notification.seatgroup_application.channel';
    }

    public function subscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->subscribeToChannel($channel_id, $via, 'seatgroup_application'))
            return redirect()->back()->with('success', 'You are going to be notified if someone applies to a SeAT Group you are managing.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function unsubscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->unsubscribeFromChannel($channel_id, 'seatgroup_application'))
            return redirect()->back()->with('success', 'You are no longer going to be notified about SeAT Group Application events.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function isSubscribed($view, $channel)
    {
        return $this->getSubscribtionStatus($channel, $view, 'seatgroup_application');
    }

    public function isDisabledButton($view, $channel) : bool
    {
        return $this->isDisabled($channel, $view, 'seatgroups.create');
    }

    public function isAvailable() : bool
    {
        return $this->hasPermission('seatgroups.create');
    }
}
