<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Seat\Services\Repositories\Corporation\Corporation;

class RemoveCorpAffiliationAction
{
    use Corporation;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data)
    {
        try{
            $group_id = $data['seatgroup_id'];
            $corporation_id = $data['corporation_id'];

            $seat_group = Seatgroup::find($group_id);

            $seat_group->corporation()->detach($corporation_id);

            return true;

        } catch (\Exception $e) {
            return false;
        }

    }
}
