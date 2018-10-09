<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 15:21.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles;

use Herpaderpaldent\Seat\SeatGroups\Models\CorporationTitleSeatgroups;

class AddCorporationTitleAffiliation
{
    public function execute(array $data)
    {

        $seatgroup_corporation_id = $data['seatgroup-corporation-id'];
        $seatgroup_id = $data['seatgroup_id'];
        $seatgroup_title_id = $data['seatgroup-title-id'];

        return CorporationTitleSeatgroups::firstOrCreate([
            'seatgroup_id' => $seatgroup_id,
            'corporation_id' => $seatgroup_corporation_id,
            'title_id' => $seatgroup_title_id,
        ]);

    }
}
