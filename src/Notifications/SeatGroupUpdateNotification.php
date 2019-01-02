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
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;

class SeatGroupUpdateNotification extends BaseNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'update'];

    protected $image;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterInfo
     */
    protected $main_character;

    /**
     * @var \Seat\Web\Models\Group
     */
    protected $group;

    protected $attached_roles = "none";

    protected $detached_roles = "none";

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
                $this->tags = array_merge($this->tags,[
                    'discord',
                ]);
                return [DiscordChannel::class];
                break;
            case 'slack':
                $this->tags = array_merge($this->tags,[
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
