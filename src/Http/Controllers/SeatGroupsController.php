<?php
/**
 * Created by PhpStorm.
 * User: Mutterschiff
 * Date: 11.02.2018
 * Time: 16:42
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Controllers;



use Seat\Web\Http\Controllers\Controller;

class SeatGroupsController extends Controller
{
    public $restful =true;

    public function get_index(){
       return View::make('seatgroups.index');
    }
    public function get_index2(){
        return view('seatgroups::index');
    }
}