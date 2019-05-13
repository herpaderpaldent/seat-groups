<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Skills;

use Herpaderpaldent\Seat\SeatGroups\Models\SkillsSeatgroups;

class AddSkillAffiliationAction
{
    public function execute(array $data)
    {
        $seatgroup_id = $data['seatgroup_id'];

        foreach($data['skill_ids'] as $skill) {
            SkillsSeatgroups::updateOrCreate([
                'seatgroup_id' => $seatgroup_id,
                'skill_id' => explode('.', $skill)[0],
                'skill_level' => explode('.', $skill)[1]
            ]);
        }

        return true;

    }
}
