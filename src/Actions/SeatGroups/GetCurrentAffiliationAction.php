<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 18:52
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationTitle;

class GetCurrentAffiliationAction
{
    public function execute(array $data)
    {
        $seatgroup = Seatgroup::find($data['seatgroup_id']);

        $affiliations = collect();

        if($seatgroup->all_corporations)
            $affiliations->push([
                'seatgroup_id' => $seatgroup->id,
                'all_corporations' => 'all_corporations'
            ]);

        $seatgroup->corporation->each(function ($corporation) use ($affiliations, $seatgroup) {
            $affiliations->push([
                'seatgroup_id' => $seatgroup->id,
                'corporation_id' => $corporation->corporation_id,
                'name' => $corporation->name
            ]);

        });

        $seatgroup->corporationTitles->each(function ($corporation_title) use ($affiliations, $seatgroup) {
            $corporation = CorporationInfo::find($corporation_title->corporation_id);
            $title_name = CorporationTitle::where('corporation_id', $corporation_title->corporation_id)
                ->where('title_id',$corporation_title->title_id)->first()->name;

            $affiliations->push([
                'seatgroup_id' => $seatgroup->id,
                'corporation_id' => $corporation->corporation_id,
                'name' => $corporation->name,
                'corporation_title' => [
                    'title_id' => $corporation_title->title_id,
                    'name' => $title_name
                ]
            ]);
        });

        return $affiliations;
    }

}