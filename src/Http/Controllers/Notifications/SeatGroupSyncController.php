<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Notifications;


use Herpaderpaldent\Seat\SeatNotifications\Http\Controllers\BaseNotificationController;

class SeatGroupSyncController extends BaseNotificationController
{
    public function getNotification() : string
    {
        return 'seatgroups::notification.seatgroup_sync.notification';
    }

    public function getPrivateView() : string
    {
        return 'seatgroups::notification.seatgroup_sync.private';
    }

    public function getChannelView() : string
    {

        return 'seatgroups::notification.seatgroup_sync.channel';
    }

    public function subscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->subscribeToChannel($channel_id, $via, 'seatgroup_sync'))
            return redirect()->back()->with('success', 'You are going to be notified about sync events from SeAT Groups.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function unsubscribeDm($via)
    {
        $channel_id = $this->getPrivateChannel($via);

        if(is_null($channel_id))
            return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');

        if($this->unsubscribeFromChannel($channel_id, 'seatgroup_sync'))
            return redirect()->back()->with('success', 'You are no longer going to be notified about deleted seatgroup_sync events.');

        return redirect()->back()->with('error', 'Something went wrong, please assure you have setup your personal delivery channel correctly.');
    }

    public function subscribeChannel()
    {

        $via = (string) request('via');
        $channel_id = (string) request('channel_id');

        if(is_null($channel_id) || is_null($channel_id))
            return abort(500);

        if($this->subscribeToChannel($channel_id, $via, 'seatgroup_sync', true))
            return redirect()->back()->with('success', 'Channel will receive seatgroup_sync notifications from now on.');

        return abort(500);
    }

    public function unsubscribeChannel($channel)
    {
        $channel_id = $this->getChannelChannelId($channel, 'seatgroup_sync');

        if(is_null($channel_id))
            return abort(500);

        if($this->unsubscribeFromChannel($channel_id, 'seatgroup_sync'))
            return redirect()->back()->with('success', 'Channel will no longer receive seatgroup_sync notifications.');

        return abort(500);
    }

    public function isSubscribed($view, $channel)
    {
        return $this->getSubscribtionStatus($channel, $view, 'seatgroup_sync');
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