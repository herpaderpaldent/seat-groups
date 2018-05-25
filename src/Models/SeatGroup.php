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
    public function manager(){

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('is_manager',1);
    }
    public function member(){

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist',0);
    }
    public function waitlist(){

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist',1);
    }

    public function isManager(){
        if(in_array(Auth::user()->group->id , $this->manager->map(function($group) { return $group->id; })->toArray()) || Auth::user()->hasRole('Superuser')) {
            return true;
        }
        return false;
    }

    public function isAllowedToSeeSeatGroup(){

        if(in_array(Auth::user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray()) || Auth::user()->hasRole('Superuser')) {
            return true;
        }
        return false;

    }

    public function onWaitlist(){
        if (in_array(Auth::user()->group->id , $this->waitlist->map(function($group) { return $group->id; })->toArray())){
            return true;
        } return false;
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
            if($this->type === "managed"){
                if(in_array(Auth::user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray())){
                    if(in_array(Auth::user()->group->id , $this->member->map(function($group) { return $group->id; })->toArray())){
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