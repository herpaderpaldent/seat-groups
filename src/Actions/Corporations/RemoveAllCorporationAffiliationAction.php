<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 06.09.2018
 * Time: 09:13.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;

class RemoveAllCorporationAffiliationAction
{
    public function execute(array $data)
    {
        try {
            $seatgroup_id = $data['seatgroup_id'];
            $seatgroup = SeatGroup::find($seatgroup_id);

            $seatgroup->all_corporations = false;
            $seatgroup->save();

            return true;
        } catch (\Exception $e){
            return false;
        }

    }
}
