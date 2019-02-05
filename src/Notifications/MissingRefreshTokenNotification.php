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
use Seat\Web\Models\User;

class MissingRefreshTokenNotification extends BaseNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'missing_refresh_token'];

    protected $image;

    /**
     * @var \Seat\Web\Models\User
     */
    protected $user;

    protected $url;

    protected $main_character;

    protected $group;

    public function __construct(User $user)
    {
        parent::__construct();

        $this->user = $user;
        $this->image = 'https://imageserver.eveonline.com/Character/' . $user->id . '_128.jpg';
        $this->url = route('configuration.users.edit', ['user_id' => $user->id]);
        $this->main_character = $this->getMainCharacter($user->group)->name;
        $this->group = $user->group;
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
                $this->tags = array_merge($this->tags, [
                    'discord',
                ]);

                return [DiscordChannel::class];
                break;
            case 'slack':
                $this->tags = array_merge($this->tags, [
                    'slack',
                ]);

                return [SlackChannel::class];
                break;
            default:
                return [''];
        }
    }

    public function toDiscord($notifiable)
    {
        $message = sprintf('The RefreshToken of [%s](%s) is missing. '
            . 'Ask the owner of this user group to login again with this user, in order to provide a new RefreshToken. '
            . 'This user group will lose all potentially gained roles through this character.',
            $this->user->name,
            $this->url
        );

        return (new DiscordMessage)
            ->embed(function ($embed) use ($message) {

                $embed->title('** Error **')
                    ->thumbnail($this->image)
                    ->color('12727073')
                    ->field('Missing Refresh Token', $message, false)
                    ->field('Main character', $this->main_character, true)
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
        $message = sprintf('The RefreshToken of %s in user group of %s (%s) is missing. '
            . 'Ask the owner of this user group to login again with this user, in order to provide a new RefreshToken. '
            . 'This user group will lose all potentially gained roles through this character.',
            $this->user->name,
            $this->main_character,
            $this->group->users->map(function ($user) {return $user->name; })
            ->implode(', ')
        );

        return (new SlackMessage)
            ->warning()
            ->attachment(function ($attachment) use ($message) {
                $attachment
                    ->title('Error', $this->url)
                    ->thumb($this->image)
                    ->color('C23321')
                    ->content($message);
            });
    }
}
