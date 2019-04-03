<?php
/**
 * Created by PhpStorm.
 * User: fehu
 * Date: 11.09.18
 * Time: 15:43.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Managers;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;

class RemoveManagerAction
{
    public function execute(array $data)
    {
        $seatgroup = SeatGroup::find($data['seatgroup_id']);
        $group_id = $data['group_id'];
        $children_id = $data['children_id'];

        if(isset($group_id)){

            $seatgroup->group()->updateExistingPivot($group_id, [
                'is_manager' => 0,
            ]);

        }

        if(isset($children_id)){

            $seatgroup->children()->detach($data['children_id']);

        }

        return redirect()->back()->with('success', 'SeAT Group removed');

    }
}
