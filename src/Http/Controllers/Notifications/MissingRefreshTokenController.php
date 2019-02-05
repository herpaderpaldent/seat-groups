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

class MissingRefreshTokenController extends BaseNotificationController
{
    public function getNotification() : string
    {
        return 'seatgroups::notification.missing_refreshtoken.notification';
    }

    public function getPrivateView() : string
    {
        return 'seatgroups::notification.missing_refreshtoken.private';
    }

    public function getChannelView() : string
    {

        return 'seatgroups::notification.missing_refreshtoken.channel';
    }

    public function subscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->subscribeToChannel($channel_id, $via, 'missing_refreshtoken'))
            return redirect()->back()->with('success', 'You are going to be notified about missing refresh_tokens every time SeAT Group Sync runs.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function unsubscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->unsubscribeFromChannel($channel_id, 'missing_refreshtoken'))
            return redirect()->back()->with('success', 'You are no longer going to be notified about missing_refreshtoken events.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function subscribeChannel()
    {

        $via = (string) request('via');
        $channel_id = (string) request('channel_id');

        if(is_null($channel_id) || is_null($channel_id))
            return abort(500);

        if($this->subscribeToChannel($channel_id, $via, 'missing_refreshtoken', true))
            return redirect()->back()->with('success', 'This channel is going to be notified about missing refresh_tokens every time SeAT Group Sync runs.');

        return abort(500);
    }

    public function unsubscribeChannel($channel)
    {
        $channel_id = $this->getChannelChannelId($channel, 'missing_refreshtoken');

        if(is_null($channel_id))
            return abort(500);

        if($this->unsubscribeFromChannel($channel_id, 'missing_refreshtoken'))
            return redirect()->back()->with('success', 'Channel will no longer receive missing_refreshtoken notifications.');

        return abort(500);
    }

    public function isSubscribed($view, $channel)
    {
        return $this->getSubscribtionStatus($channel, $view, 'missing_refreshtoken');
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