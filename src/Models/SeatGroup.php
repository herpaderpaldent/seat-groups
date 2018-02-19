<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Illuminate\Database\Eloquent\Model;

use Seat\Web\Models\User;

class Seatgroup extends Model
{
    protected $fillable = ['name','description','type','role_id' ];

    // TODO: Check if Validation is needed:



    public function role(){
        return $this->belongsTo('Seat\Web\Models\Acl\Role', 'role_id','id');
    }
    public function isManager(User $user, int $group){
        if(Seatgroupmanager::where('group_id','=',$group)->where('user_id', '=', $user['id'])->count() >0){
            return true;
        } else {return false;}
    }
    public function listManager(int $group){
        $managersHelper= Seatgroupmanager::where('group_id','=',$group)->get();

        //
        /**
         * TODO: when a Seatgroupmanager is created with the relation extend this function to call managers
         *
         *
         */

        $helper2 = $managersHelper->users();

        if(true){
            return (string) null;
        } else return null;
    }
}