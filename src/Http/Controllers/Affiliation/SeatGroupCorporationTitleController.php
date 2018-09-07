<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 13:49
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;



use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles\AddCorporationTitleAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles\GetCorporationTitleAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\Titles\RemoveCorporationTitleAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\AddCorporationTitleAffiliationRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\RemoveCorporationTitleAffiliationRequest;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\ResolveCorporationTitleRequest;

class SeatGroupCorporationTitleController
{
    public function getCorporationTitles(ResolveCorporationTitleRequest $resolve_corporation_title, GetCorporationTitleAction $action)
    {

        $response = $action->execute($resolve_corporation_title->all());

        return response()->json($response);

    }

    public function addCorporationTitleAffiliation (AddCorporationTitleAffiliationRequest $request, AddCorporationTitleAffiliation $action)
    {
        if ($action->execute($request->all()))
            return redirect()->back()->with('success', 'Corporation title affiliation added.');

        return redirect()->back()->with('warning', 'Ups something went wrong');


    }
    public function removeCorporationTitleAffiliation (RemoveCorporationTitleAffiliationRequest $request, RemoveCorporationTitleAffiliationAction  $action)
    {
        if ($action->execute($request->all()))
            return redirect()->back()->with('success', 'Corporation title affiliation removed.');

        return redirect()->back()->with('warning', 'Ups something went wrong');


    }

}