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

use Herpaderpaldent\Seat\SeatNotifications\Channels\Discord\DiscordChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Discord\DiscordMessage;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackChannel;
use Herpaderpaldent\Seat\SeatNotifications\Channels\Slack\SlackMessage;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\BaseNotification;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;

class SeatGroupSyncNotification extends BaseNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'sync'];

    protected $image;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterInfo
     */
    protected $main_character;

    /**
     * @var \Seat\Web\Models\Group
     */
    protected $group;

    protected $attached_roles = 'none';

    protected $detached_roles = 'none';

    public function __construct(Group $group, array $sync)
    {
        parent::__construct();

        $this->group = $group;
        $this->main_character = $this->getMainCharacter($group);
        $this->image = 'https://imageserver.eveonline.com/Character/' . $this->main_character->character_id . '_128.jpg';

        if (! empty($sync['attached']))
            $this->attached_roles = Role::whereIn('id', $sync['attached'])->pluck('title')->implode(', ');

        if (! empty($sync['detached']))
            $this->detached_roles = Role::whereIn('id', $sync['detached'])->pluck('title')->implode(', ');

        array_push($this->tags, 'group_id:' . $group->id);
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

        $main_character = sprintf('The user group of %s has just been updated.',
            $this->main_character->name);

        $users = sprintf('Users in user group: %s',
            $this->group->users->map(function ($user) {

            return $user->name;
        })->implode(', '));

        return (new DiscordMessage)
            ->embed(function ($embed) use ($main_character, $users) {

                $embed->title('** ' . $main_character . ' **')
                    ->thumbnail($this->image)
                    ->color('1548984')
                    ->description($users)
                    ->field('Attachhed roles', $this->attached_roles, true)
                    ->field('Detached roles', $this->detached_roles, true);
            });
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
                    ->color('17A2B8')
                    ->thumb($this->image);
            });
    }
}
