<?php

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;

use Herpaderpaldent\Seat\SeatGroups\Actions\Skills\AddSkillAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Skills\RemoveSkillAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\AddSkillAffiliationRequest;
use Herpaderpaldent\Seat\SeatGroups\Validation\Affiliation\RemoveSkillAffiliationRequest;

class SeatGroupSkillsController
{
    public function removeSkill(RemoveSkillAffiliationRequest $request, RemoveSkillAffiliationAction $action)
    {
        if($action->execute($request->all()))
            return redirect()->back()->with('success', 'removed');

        return redirect()->back()->with('warning', 'something went wrong');
    }

    public function addSkillAffiliation(AddSkillAffiliationRequest $request, AddSkillAffiliationAction $action)
    {

        if ($action->execute($request->all())) {
            return redirect()->back()->with('success', 'Updated');
        }

        return redirect()->back()->with('warning', 'Ups something went wrong');

    }
}
