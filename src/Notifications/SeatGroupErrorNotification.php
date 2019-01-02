<?php
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

        switch($notifiable->via) {
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
