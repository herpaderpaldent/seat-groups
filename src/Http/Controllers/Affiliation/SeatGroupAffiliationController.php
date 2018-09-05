<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 18:48
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;


use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\GetCurrentAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation\GetCurrentAffiliationsRequest;
use Illuminate\Routing\Controller;
use Yajra\Datatables\Datatables;

class SeatGroupAffiliationController extends Controller
{
    public function getCurrentAffiliations(GetCurrentAffiliationsRequest $request, GetCurrentAffiliationAction $action)
    {

        $affiliations = $action->execute($request->all());


        return Datatables::of($affiliations)
            /*->editColumn('created_at', function($row){
                return view('seatgroups::logs.partials.date', compact('row'));
            })
            ->editColumn('event', function ($row) {
                return view('seatgroups::logs.partials.event', compact('row'));
            })*/
            ->make(true);

    }

}