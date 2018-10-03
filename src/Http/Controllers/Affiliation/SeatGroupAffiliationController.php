<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 18:48.
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

        $affiliations = collect($action->execute($request->all()));

        return Datatables::of($affiliations)
            ->addColumn('affiliation', function ($row) {
                return view('seatgroups::affiliation.partials.table-partials.affiliation', compact('row'))->render();
            })
            ->addColumn('remove', function ($row) {
                return view('seatgroups::affiliation.partials.table-partials.remove-button', compact('row'))->render();
            })
            ->make(true);

    }
}
