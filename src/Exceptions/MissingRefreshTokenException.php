<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 04.09.2018
 * Time: 08:57.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Exceptions;

use Exception;
use Seat\Web\Models\User;

class MissingRefreshTokenException extends Exception
{
    public function __construct(User $user)
    {
        $message = sprintf('The user group with ID %d is missing a refresh_token from %s (%d) ' .
            'therefore the user group loses all its roles and permissions. To fix this: ask the user to login to SeAT again.', $user->group_id, $user->name, $user->id);

        parent::__construct($message, 0, null);
    }
}
