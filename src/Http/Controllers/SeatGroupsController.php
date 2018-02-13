<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 22:24
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;


use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup_Group;
use Seat\Web\Http\Controllers\Controller;

class SeatGroupsController extends Controller
{
    public function index(){
        return view('seatgroups::index')
            ->with('groupname',SeatGroup_Group::all());
    }
}