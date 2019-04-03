<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 16:42.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Seat\Eveapi\Models\Corporation\CorporationTitle;

class GetCorporationTitleAction
{
    public function execute(array $data)
    {
        $corporationId = $data['corporation_id'];
        $seatgroup_id = $data['seatgroup_id'];

        $existing_affiliations = SeatGroup::find($seatgroup_id)
            ->corporationTitles
            ->where('corporation_id', $corporationId)
            ->pluck('title_id');

        if (! empty($corporationId)) {
            $titles = CorporationTitle::where('corporation_id', $corporationId)
                ->whereNotIn('title_id', $existing_affiliations)
                ->select('title_id', 'name')
                ->get();

            return $titles->map(function ($item) {
                return [
                    'title_id' => $item->title_id,
                    'name' => strip_tags($item->name),
                ];
            });
        }

        return [];
    }
}
