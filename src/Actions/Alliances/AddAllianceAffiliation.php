<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 10.10.2018
 * Time: 18:58.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Alliances;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;

class AddAllianceAffiliation
{
    public function execute(array $data)
    {
        $seat_group_id = $data['seatgroup_id'];
        $alliance_ids = $data['alliance_ids'];

        $seat_group = SeatGroup::find($seat_group_id);

        $seat_group->alliance()->attach($alliance_ids);

        return true;
    }
}
