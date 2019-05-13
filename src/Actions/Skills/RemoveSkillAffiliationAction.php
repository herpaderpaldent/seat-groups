<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Skills;

use Herpaderpaldent\Seat\SeatGroups\Models\SkillsSeatgroups;

class RemoveSkillAffiliationAction
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data)
    {
        try{
            $group_id = $data['seatgroup_id'];
            $skill_id = $data['skill_id'];
            $skill_level = $data['skill_level'];

            SkillsSeatgroups::where('seatgroup_id', $group_id)->where('skill_id', $skill_id)->where('skill_level', $skill_level)->delete();

            return true;

        } catch (\Exception $e) {
            return false;
        }

    }
}
