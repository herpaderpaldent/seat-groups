<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 27.06.2018
 * Time: 22:07.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;

class DeleteSeatGroup
{
    public function execute(array $data)
    {
        $group_id = $data['seatgroup_id'];

        $seatgroup = Seatgroup::find($group_id);
        $seatgroup->delete();

        return true;
    }
}
