<?php
/**
 * Created by PhpStorm.
 * User: fehu
 * Date: 10.09.18
 * Time: 15:31.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Managers;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;

class AddManagerAction
{
    public function execute(array $data)
    {
        $seatgroup = SeatGroup::find($data['seatgroup_id']);

        if(isset($data['groups'])){
            $groups = $data['groups'];

            foreach ($groups as $group) {
                if (in_array($group, $seatgroup->waitlist->map(function ($group) { return $group->id; })->toArray())) {
                    return redirect()->back()->with('warning', 'User must be first member before made manager');
                }
                elseif (in_array($group, $seatgroup->member->map(function ($group) { return $group->id; })->toArray())) {
                    $seatgroup->group()->updateExistingPivot($group, [
                        'is_manager' => 1,
                    ]);
                } else {
                    $seatgroup->group()->attach($group, [
                        'is_manager' => 1,
                    ]);
                }
            }
        }

        if(isset($data['seatgroups'])){

            $seatgroup->children()->attach($data['seatgroups']);

        }

        return redirect()->back()->with('success', 'Updated');
    }
}
