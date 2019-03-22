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


use Herpaderpaldent\Seat\SeatNotifications\Notifications\AbstractNotification;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;

class AbstractSeatGroupSyncNotification extends AbstractNotification
{
    /**
     * @var array
     */
    public $tags = ['seat_group', 'sync'];

    /**
     * @var string
     */
    public $image;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterInfo
     */
    public $main_character;

    /**
     * @var \Seat\Web\Models\Group
     */
    public $group;

    /**
     * @var string
     */
    public $attached_roles = 'none';

    /**
     * @var string
     */
    public $detached_roles = 'none';

    /**
     * AbstractSeatGroupSyncNotification constructor.
     *
     * @param \Seat\Web\Models\Group $group
     * @param array                  $sync
     */
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
     * Return a title for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getTitle(): string
    {
        return 'SeAT Group Sync';
    }

    /**
     * Return a description for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Receive a notification about attached or detached roles to user groups.';
    }

    /**
     * Determine if a notification can target public channel (forum category, chat, etc...).
     * @return bool
     */
    public static function isPublic(): bool
    {
        return true;
    }

    /**
     * Determine if a notification can target personal channel (private message, e-mail, etc...).
     * @return bool
     */
    public static function isPersonal(): bool
    {
        return true;
    }
}