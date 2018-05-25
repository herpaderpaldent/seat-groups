<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Models\User;

class Seatgroup extends Model
{
    use Corporation;
    protected $fillable = ['name','description','type','role_id' ];

    public function role(){
        return $this->belongsToMany('Seat\Web\Models\Acl\Role');
    }

    public function group(){
        return $this->belongsToMany('Seat\Web\Models\Group')
            ->withPivot('is_manager','on_waitlist');
    }

    public function corporation(){
        return $this->belongsToMany('Seat\Eveapi\Models\Corporation\CorporationInfo',
            'corporation_info_seatgroup','seatgroup_id', 'corporation_id');
    }

    /*public function corporation2(){
        return $this->$this->belongsToMany('Seat\Eveapi\Models\Corporation\CorporationInfo');
    }*/

    /*public function manager()
    {
        return $this->belongsToMany('Seat\Web\Models\Group', 'seatgroup_user','user_id')
            ->wherePivot('is_manager',"=", true);
    }*/

    public function isAllowedToSeeSeatGroup(){

        if(in_array(Auth::user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray()) || Auth::user()->hasRole('Superuser')) {
            return true;
        }
        return false;

    }

    public function isManager(int $user, int $groupint){


        // TODO: clear this up create checker for manager
        return true;




    }
    public function listManager(int $group){
        $managersHelper= Seatgroupmanager::where('group_id','=',$group)->get();

        $seatgroup = Seatgroup::find($group);



        if(true){
            return (string) null;
        } else return null;
    }
    public function isMember(){

        try{
            if($this->open === "auto"){
                if(in_array(Auth::user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray())){
                        return true;
                }
            }
            if($this->type === "open"){
                if(in_array(Auth::user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray())){
                    if(in_array(Auth::user()->group->id , $this->group->pluck('id')->toArray())){
                        return true;
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }



    }
}