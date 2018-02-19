<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:42
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Illuminate\Database\Eloquent\Model;

class Seatgroupmanager extends Model
{
    public function user(){
        return $this->belongsToMany('Seat\Web\Models\User');
    }
}