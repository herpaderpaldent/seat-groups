<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Seat;

use Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException;
use Seat\Web\Models\Group;

class GetMainCharacterAction
{
    public function execute(Group $group)
    {
        $main_character = $group->main_character;

        if (is_null($main_character)) {
            logger()->warning('Group has no main character set. Attempt to make assignation based on first attached character.', [
                'group_id' => $group->id,
            ]);
            $main_character = optional($group->users()->has('character')->first())->character;
        }

        if (is_null($main_character))
            throw new MissingMainCharacterException($group);

        return $main_character;

    }
}
