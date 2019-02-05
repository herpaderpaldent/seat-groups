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

class SeatGroupErrorNotification extends BaseNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'error'];

    protected $image;

    /**
     * @var \Seat\Web\Models\User
     */
    protected $user;

    protected $message;

    protected $url;

    public function __construct(User $user, string $message)
    {
        parent::__construct();

        $this->user = $user;
        $this->image = 'https://imageserver.eveonline.com/Character/' . $this->user->id . '_128.jpg';
        $this->message = $message;
        $this->url = route('character.view.sheet', ['character_id' => $this->user->id]);
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

        return (new DiscordMessage)
            ->embed(function ($embed) {

                $embed
                    ->title('** Error **', $this->url)
                    ->thumbnail($this->image)
                    ->color('12727073')
                    ->description($this->message);
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
            ->warning()
            ->attachment(function ($attachment) {
                $attachment
                    ->title('Error', $this->url)
                    ->thumb($this->image)
                    ->color('C23321')
                    ->content($this->message);
            });
    }
}
