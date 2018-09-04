<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 04.09.2018
 * Time: 13:33
 */

namespace Herpaderpaldent\Seat\SeatGroups\Observers;

use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupSync;
use Seat\Eveapi\Models\RefreshToken;

class RefreshTokenObserver
{
    public function deleting(RefreshToken $refresh_token)
    {
        logger()->debug('SoftDelete detected of '. $refresh_token->user->name);

        dispatch(new GroupSync($refresh_token->user->group));

    }

}