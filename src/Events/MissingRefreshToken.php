<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 22:40
 */

namespace Herpaderpaldent\Seat\SeatGroups\Events;


use Illuminate\Queue\SerializesModels;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

class MissingRefreshToken
{
    use SerializesModels;

    public $user;

    public $main_character;

    public function __construct(User $user, CharacterInfo $main_character)
    {
        $this->user = $user;
        $this->main_character = $main_character;
    }

}