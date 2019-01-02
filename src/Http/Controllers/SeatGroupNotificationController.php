<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 02.01.2019
 * Time: 10:29
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;


use Herpaderpaldent\Seat\SeatGroups\Http\Validation\AddSeatGroupNotificationSubscriptionRequest;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroupNotification;
use Herpaderpaldent\Seat\SeatNotifications\Http\Controllers\BaseNotificationController;

class SeatGroupNotificationController extends BaseNotificationController
{
    public function getNotification() : string
    {
        return 'seatgroups::notification.notification';
    }

    public function getPrivateView() : string
    {
        return 'seatgroups::notification.private';
    }

    public function getChannelView() : string
    {

        return 'seatgroups::notification.channel';
    }

    public function subscribeChannel(AddSeatGroupNotificationSubscriptionRequest $request)
    {

        SeatGroupNotification::updateOrCreate(
            ['channel_id' => $request->input('channel_id')],
            ['via' => $request->input('via')]
        );

        return redirect()->back()->with('success', 'Channel will receive SeAT groups notifications from now on.');
    }

    public function unsubscribeChannel($via)
    {

        SeatGroupNotification::where('via', $via)
            ->delete();

        return redirect()->back()->with('success', 'Channel will not receive SeAT groups notifications from this point on.');
    }

    public function isDisabledButton($channel) : bool
    {
        // return false if slack has not been setup
        if($channel === 'slack') {
            if(is_null(setting('herpaderp.seatnotifications.slack.credentials.token', true)))
                return true;
        }

        // return false if discord has not been setup
        if($channel === 'discord') {
            if(is_null(setting('herpaderp.seatnotifications.discord.credentials.bot_token', true)))
                return true;
        }

        return false;
    }

    public function isSubscribed($via) : bool
    {
        return SeatGroupNotification::where('via', $via)->count() > 0 ? true : false;
    }

}