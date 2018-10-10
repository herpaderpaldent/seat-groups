<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 10.10.2018
 * Time: 16:16.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Alliances;

use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\GetCorporationListAction;
use Seat\Eveapi\Models\Alliances\Alliance;

class GetAllianceListAction
{
    private $corpList;

    public function __construct(GetCorporationListAction $get_corporation_list_action)
    {
        $this->corpList = $get_corporation_list_action;
    }

    public function execute(array $data)
    {
        $all_corporations = $this->corpList->execute([
            'seatgroup_id' =>$data['seatgroup_id'],
            'origin' => 'SeatGroupsController',
        ]);

        $alliance_ids = $all_corporations->pluck('alliance_id')->unique();

        return Alliance::whereIn('alliance_id', $alliance_ids)->select('alliance_id', 'name')->get();

    }
}
