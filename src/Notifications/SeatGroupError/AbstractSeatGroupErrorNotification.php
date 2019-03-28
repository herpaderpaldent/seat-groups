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

use Herpaderpaldent\Seat\SeatNotifications\Notifications\AbstractNotification;
use Seat\Web\Models\User;

abstract class AbstractSeatGroupErrorNotification extends AbstractNotification
{
    /**
     * @var array
     */
    protected $tags = ['seat_group', 'error'];

    /**
     * @var string
     */
    protected $image;

    /**
     * @var \Seat\Web\Models\User
     */
    protected $user;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $url;

    /**
     * AbstractSeatGroupErrorNotification constructor.
     *
     * @param \Seat\Web\Models\User $user
     * @param string                $message
     */
    public function __construct(User $user, string $message)
    {
        parent::__construct();

        $this->user = $user;
        $this->image = 'https://imageserver.eveonline.com/Character/' . $this->user->id . '_128.jpg';
        $this->message = $message;
        $this->url = route('character.view.sheet', ['character_id' => $this->user->id]);
    }

    /**
     * Return a title for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getTitle(): string
    {
        return 'SeAT Group Error';
    }

    /**
     * Return a description for the notification which will be displayed in UI notification list.
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Receive a notification about error during SeAT Group Syncs.';
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

    /**
     * Determine the permission needed to represent driver buttons.
     * @return string
     */
    public static function getPermission(): string
    {
        return 'seatgroups.error_notification';
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    abstract public function via($notifiable);

    /**
     * @param $notifiable
     *
     * @return bool
     * @throws \Exception
     */
    public function dontSend($notifiable) :bool
    {
        $value = collect([
            'recipient' => $notifiable->driver_id,
            'notification' => get_called_class(),
            'content' => get_object_vars($this),
        ])->toJson();

        $key = sha1($value);

        if (empty(cache($key))) {
            cache([$key => $value], now()->addHours(4));

            return false;
        }

        return true;
    }
}
