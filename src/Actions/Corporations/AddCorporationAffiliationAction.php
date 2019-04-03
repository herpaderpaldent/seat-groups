<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Seat\Services\Repositories\Corporation\Corporation;

class AddCorporationAffiliationAction
{
    use Corporation;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data)
    {

        $seat_group_id = $data['seatgroup_id'];
        $corporations = $data['corporation_ids'];

        $seat_group = SeatGroup::find($seat_group_id);

        if(in_array('-1', $corporations)){
            // First set SeAT Group to $all_corporation = true
            $seat_group->all_corporations = true;
            $seat_group->save();

            // Secondly remove the -1 value from the array
            $corporations = array_filter($corporations, function ($value) {
                return $value !== '-1';
            });
        }

        $seat_group->corporation()->attach($corporations);

        return true;

    }
}
