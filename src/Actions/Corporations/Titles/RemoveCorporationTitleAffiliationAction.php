<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 06.09.2018
 * Time: 09:56.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles;

use Herpaderpaldent\Seat\SeatGroups\Models\CorporationTitleSeatgroups;

class RemoveCorporationTitleAffiliationAction
{
    public function execute(array $data)
    {
        try{
            $corporation_id = $data['corporation_id'];
            $seatgroup_id = $data['seatgroup_id'];
            $title_id = $data['title_id'];

            CorporationTitleSeatgroups::where('corporation_id', $corporation_id)
                ->where('title_id', $title_id)
                ->where('seatgroup_id', $seatgroup_id)
                ->delete();

            return true;
        } catch (\Exception $e){
            return false;
        }

    }
}
