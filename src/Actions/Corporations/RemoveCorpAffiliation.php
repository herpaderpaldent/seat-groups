<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Http\Validation\RemoveAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Corporation\Corporation;


class RemoveCorpAffiliation
{
    use Corporation;
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data)
    {

        $group_id = $data['seatgroup_id'];
        $corporation_id = $data['corporation_id'];

        $seat_group = Seatgroup::find($group_id);

        if($corporation_id === "1337"){
            $seat_group->all_corporations = false;
            $seat_group->save();
            return "All Corporations";
        }


        $seat_group->corporation()->detach($corporation_id);

        return $this->getCorporationSheet($corporation_id)->name;

    }

}