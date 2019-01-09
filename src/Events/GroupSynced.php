<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.01.2019
 * Time: 20:20.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Events;

use Illuminate\Queue\SerializesModels;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\Group;

class GroupSynced
{
    use SerializesModels;

    public $group;

    public $main_character;

    public $sync;

    public function __construct(Group $group, CharacterInfo $main_character, array $sync)
    {
        $this->group = $group;
        $this->main_character = $main_character;
        $this->sync = $sync;
    }
}
