<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 17:52
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers\Affiliation;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\GetCorporationListAction;
use Herpaderpaldent\Seat\SeatGroups\Http\Validation\GetCorporationListRequest;

class SeatGroupCorporationController
{
    public function getCorporationList(GetCorporationListRequest $request, GetCorporationListAction $action)
    {

        $response = $action->execute($request->all());

        return response()->json($response);

    }

}