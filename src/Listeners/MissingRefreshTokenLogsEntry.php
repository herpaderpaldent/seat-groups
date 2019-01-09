<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 09.01.2019
 * Time: 19:12
 */

namespace Herpaderpaldent\Seat\SeatGroups\Listeners;


use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Illuminate\Contracts\Queue\ShouldQueue;

class MissingRefreshTokenLogsEntry implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(MissingRefreshToken $event)
    {

        $message = sprintf('The RefreshToken of %s in user group of %s (%s) is missing. '
            . 'Ask the owner of this user group to login again with this user, in order to provide a new RefreshToken. '
            . 'This user group will lose all potentially gained roles through this character.',
            $event->user->name, $event->main_character->name, $event->user->group->users->map(function ($user) {return $user->name; })
                ->implode(', ')
        );

        SeatgroupLog::create([
            'event'   => 'error',
            'message' => $message,
        ]);

    }

}