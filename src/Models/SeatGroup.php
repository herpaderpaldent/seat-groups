<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Illuminate\Database\Eloquent\Model;

class Seatgroup extends Model
{
    public function role(){
        return $this->belongsTo('Seat\Web\Models\Acl\Role', 'role_id','id');
    }
}