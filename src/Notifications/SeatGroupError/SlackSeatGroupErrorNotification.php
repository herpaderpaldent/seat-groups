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

namespace Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupError;


use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage;
use Seat\Web\Models\Group;

class SlackSeatGroupErrorNotification extends AbstractSeatGroupErrorNotification
{
    const DANGER_COLOR = '#DD4B39';

    /**
     * Determine if channel has personal notification setup.
     *
     * @return bool
     */
    public static function hasPersonalNotification() : bool
    {
        return true;
    }

    /**
     * @param $notifiable
     *
     * @return mixed
     */
    public function via($notifiable)
    {
        array_push($this->tags, is_null($notifiable->group_id) ? 'to channel' : 'private to: '
            . $this->getMainCharacter(Group::find($notifiable->group_id))->name);

        return [SlackChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return \Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->attachment(function ($attachment) {
                $attachment
                    ->title('Error', $this->url)
                    ->thumb($this->image)
                    ->color(self::DANGER_COLOR)
                    ->content($this->message);
            });
    }
}