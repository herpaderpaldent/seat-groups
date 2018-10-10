<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 17:57.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

class GetCorporationListAction //TODO: Refactor this Class and rename
{
    public function execute(array $data)
    {

        $seatgroup = Seatgroup::find($data['seatgroup_id']);

        $existing_affiliations = collect();

        if($seatgroup->all_corporations && ! $data['origin'] === 'SeatGroupsController')
            return;

        //if either corporation or a corporation-title is assigned don't show it in available list
        $existing_affiliations->push($seatgroup->corporation->pluck('corporation_id'));

        if($data['origin'] != 'corporation-tile-form')
            $existing_affiliations->push($seatgroup->corporationTitles->pluck('corporation_id'));

        //Add all corporation in alliance to existing_affiliation
        if($seatgroup->alliance->isNotEmpty()){
            $corp_ids = CorporationInfo::whereIn('alliance_id', $seatgroup->alliance->pluck('alliance_id'))
                ->select('corporation_id')->get()->pluck('corporation_id');
            $existing_affiliations->push($corp_ids);
        }

        $existing_affiliations = $existing_affiliations->filter(function ($affiliation) {
            return ! $affiliation->isEmpty();
        })->flatten()->unique();

        if($existing_affiliations->isEmpty())
            return CorporationInfo::select('corporation_id', 'name', 'alliance_id')->get();

        return CorporationInfo::whereNotIn('corporation_id', $existing_affiliations)
            ->select('corporation_id', 'name', 'alliance_id')
            ->get();
    }
}
