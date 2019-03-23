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

return [

    'seat-notification' => [
        Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupSync\AbstractSeatGroupSyncNotification::class => [
            'discord' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupSync\DiscordSeatGroupSyncNotification::class,
            'slack' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupSync\SlackSeatGroupSyncNotification::class,
        ],
        Herpaderpaldent\Seat\SeatGroups\Notifications\MissingRefreshToken\AbstractMissingRefreshTokenNotification::class => [
            'discord' => Herpaderpaldent\Seat\SeatGroups\Notifications\MissingRefreshToken\DiscordMissingRefreshTokenNotification::class,
            'slack' => Herpaderpaldent\Seat\SeatGroups\Notifications\MissingRefreshToken\SlackMissingRefreshTokenNotification::class,
        ],
        Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupApplication\AbstractSeatGroupApplicationNotification::class => [
            'discord' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupApplication\DiscordSeatGroupApplicationNotification::class,
            'slack' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupApplication\SlackSeatGroupApplicationNotification::class,
        ],
        Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupError\AbstractSeatGroupErrorNotification::class => [
            'discord' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupError\DiscordSeatGroupErrorNotification::class,
            'slack' => Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupError\SlackSeatGroupErrorNotification::class,
        ],
    ],

];
