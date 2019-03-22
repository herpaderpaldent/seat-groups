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

namespace Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupApplication;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatNotifications\Notifications\AbstractNotification;
use Seat\Web\Models\Group;

abstract class AbstractSeatGroupApplicationNotification extends AbstractNotification
{

    /**
     * @var array
     */
    public $tags = ['seat_group', 'application'];

    /**
     * @var string
     */
    public $image;

    /**
     * @var \Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup
     */
    public $seatgroup;

    /**
     * @var string
     */
    public $url;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterInfo
     */
    public $main_character;

    /**
     * @var string
     */
    public $seatgroup_string;

    /**
     * @var string
     */
    public $usergroup_string;

    /**
     * @var string
     */
    public $pending_applications;

    /**
     * @var \Seat\Web\Models\Group
     */
    public $group;

    /**
     * AbstractSeatGroupApplicationNotification constructor.
     *
     * @param \Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup $seatgroup
     * @param \Seat\Web\Models\Group                            $group
     */
    public function __construct(Seatgroup $seatgroup, Group $group)
    {
        parent::__construct();

        $this->group = $group;
        $this->seatgroup = $seatgroup;

        $this->main_character = $this->getMainCharacter($group);
        $this->image = 'https://imageserver.eveonline.com/Character/' . $this->main_character->character_id . '_128.jpg';
        $this->url = route('seatgroups.index') . '#managed_group';

        $this->seatgroup_string = (string) $this->seatgroup->name;
        $this->usergroup_string = (string) $this->group->users->map(function ($user) {return $user->name; })->implode(', ');

        $applications_helper = $this->seatgroup
            ->waitlist
            ->filter(function ($group) {
                return $group->id !== $this->group->id;
            })
            ->map(function ($group) {
                return $this->getMainCharacter($group)->name;
            });

        $this->pending_applications = (string) $applications_helper->isNotEmpty() ? $applications_helper->implode(', ') : 'none';
    }

    /**
     * Return a title for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getTitle(): string
    {
        return 'SeAT Group Applications';
    }

    /**
     * Return a description for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Receive a notification about new SeAT Group candidates which are on the wait list.';
    }

    /**
     * Determine if a notification can target public channel (forum category, chat, etc...).
     * @return bool
     */
    public static function isPublic(): bool
    {
        return false;
    }

    /**
     * Determine if a notification can target personal channel (private message, e-mail, etc...).
     * @return bool
     */
    public static function isPersonal(): bool
    {
        return true;
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    abstract public function via($notifiable);
}