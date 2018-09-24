<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 17:52
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\RemoveAllCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\RemoveCorpAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\AddCorporationAffiliationRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\RemoveAllCorporationsRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\RemoveCorporationAffiliationRequest;

class SeatGroupCorporationController
{
    public function removeAllCorporations(RemoveAllCorporationsRequest $request, RemoveAllCorporationAffiliationAction $action)
    {

        if($action->execute($request->all()))
            return redirect()->back()->with('success', 'removed');

        return redirect()->back()->with('warning', 'something went wrong');

    }

    public function removeCorporation(RemoveCorporationAffiliationRequest $request, RemoveCorpAffiliationAction $action)
    {
        if($action->execute($request->all()))
            return redirect()->back()->with('success', 'removed');

        return redirect()->back()->with('warning', 'something went wrong');
    }

    public function addCorporationAffiliation(AddCorporationAffiliationRequest $request, AddCorporationAffiliationAction $action)
    {

        if ($action->execute($request->all())) {
            return redirect()->back()->with('success', 'Updated');
        }

        return redirect()->back()->with('warning', 'Ups something went wrong');

    }



}