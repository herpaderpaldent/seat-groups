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
 *  * User: Herpaderp Aldent
 * Date: 05.07.2018
 * Time: 15:53.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Notifications;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Discord\DiscordChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Discord\DiscordMessage;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\BaseNotification;
use Seat\Web\Models\Group;

class SeatGroupApplicationNotification extends BaseNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'application'];

    protected $image;

    protected $seatgroup;

    protected $url;

    protected $main_character;

    /**
     * @var \Seat\Web\Models\Group
     */
    protected $group;

    public function __construct(Seatgroup $seatgroup, Group $group)
    {
        parent::__construct();

        $this->group = $group;
        $this->seatgroup = $seatgroup;

        $this->main_character = $this->getMainCharacter($group);
        $this->image = 'https://imageserver.eveonline.com/Character/' . $this->main_character->character_id . '_128.jpg';
        $this->url = route('seatgroups.index') . '#managed_group';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        switch($notifiable->notification_channel) {
            case 'discord':
                array_push($this->tags, 'discord');

                return [DiscordChannel::class];
                break;
            case 'slack':
                array_push($this->tags, 'slack');

                return [SlackChannel::class];
                break;
            default:
                return [''];
        }
    }

    public function toDiscord($notifiable)
    {

        return (new DiscordMessage)
            ->embed(function ($embed) {

                $embed->title('** New Application for a managed SeAT Group **')
                    ->thumbnail($this->image)
                    ->color('1548984')
                    ->description(sprintf('%s just applied to a SeAT Group you are managing. Head over to [SeAT Groups](%s) and accept or deny the candidate.',
                        $this->main_character->name, $this->url))
                    ->field('SeAT Group', $this->seatgroup->name, true)
                    ->field('User group', $this->group->users->map(function ($user) {return $user->name; })->implode(', '), true);
            });
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
                    ->title('New Application for a managed SeAT Group', $this->url)
                    ->thumb($this->image)
                    ->color('17A2B8')
                    ->content(sprintf('%s just applied to a SeAT Group you are managing. Head over to SeAT Groups and accept or deny the candidate.',
                        $this->main_character->name))
                    ->fields([
                        'SeAT Group' => $this->seatgroup->name,
                        'User group' => $this->group->users->map(function ($user) {return $user->name; })->implode(', '),
                    ]);
            });
    }
}
