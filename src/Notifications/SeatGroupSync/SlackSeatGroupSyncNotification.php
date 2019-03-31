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

namespace Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupSync;

use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage;
use Seat\Web\Models\Group;

class SlackSeatGroupSyncNotification extends AbstractSeatGroupSyncNotification
{
    const INFO_COLOR = '17A2B8';

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
     * @return array
     * @throws \Exception
     */
    public function via($notifiable)
    {
        if($this->dontSend($notifiable))
            return [];

        array_push($this->tags, is_null($notifiable->group_id) ? 'to channel' : 'private to: ' . $this->getMainCharacter(Group::find($notifiable->group_id))->name);

        return [SlackChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return \Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $main_character = sprintf('The user group of %s has just been updated.',
            $this->main_character->name);

        $users = sprintf('Users in user group: %s',
            $this->group->users->map(function ($user) {

                return $user->name;
            })->implode(', '));

        return (new SlackMessage)
            ->attachment(function ($attachment) use ($main_character, $users) {
                $attachment
                    ->title($main_character, route('character.view.sheet', ['character_id' => $this->main_character->character_id]))
                    ->fields([
                        'Attachhed roles' => $this->attached_roles,
                        'Detached roles' => $this->detached_roles,
                    ])
                    ->content($users)
                    ->color(self::INFO_COLOR)
                    ->thumb($this->image);
            });
    }
}
