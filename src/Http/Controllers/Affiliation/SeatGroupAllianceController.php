<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 17:52.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;

use Herpaderpaldent\Seat\SeatGroups\Actions\Alliances\AddAllianceAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Actions\Alliances\RemoveAllianceAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\AllianceAffiliationRequest;


class SeatGroupAllianceController
{
    public function removeAllianceAffiliation(AllianceAffiliationRequest $request,  RemoveAllianceAffiliation $action)
    {
        if($action->execute($request->all()))
            return redirect()->back()->with('success', 'Alliance removed');

        return redirect()->back()->with('warning', 'something went wrong');
    }

    public function addAllianceAffiliation(AllianceAffiliationRequest $request, AddAllianceAffiliation $action)
    {

        if ($action->execute($request->all())) {
            return redirect()->back()->with('success', 'Alliance added');
        }

        return redirect()->back()->with('warning', 'Ups something went wrong');

    }
}
