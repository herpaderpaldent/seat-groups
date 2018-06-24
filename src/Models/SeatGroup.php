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

    public function role()
    {
        return $this->belongsToMany('Seat\Web\Models\Acl\Role');
    }

    public function group()
    {
        return $this->belongsToMany('Seat\Web\Models\Group')
            ->withPivot('is_manager','on_waitlist');
    }

    public function corporation()
    {
        return $this->belongsToMany('Seat\Eveapi\Models\Corporation\CorporationInfo',
            'corporation_info_seatgroup','seatgroup_id', 'corporation_id');
    }

    public function manager()
    {
        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('is_manager',1);
    }

    public function member()
    {
        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist',0);
    }

    public function waitlist()
    {
        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist',1);
    }

    public function isManager()
    {
        return in_array(auth()->user()->group->id , $this->manager->map(function($group) {
                return $group->id;
            })->toArray()) || auth()->user()->hasSuperUser();
    }

    public function isAllowedToSeeSeatGroup()
    {
        if ($this->all_corporations)
            return true;

        if (auth()->user()->hasSuperUser())
            return true;

        return in_array(auth()->user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray()) || auth()->user()->hasRole('seatgroups.edit');
    }

    public function onWaitlist()
    {
        return in_array(auth()->user()->group->id , $this->waitlist->map(function($group) { return $group->id; })->toArray());
    }

    public function isMember()
    {
        try {
            switch ($this->type) {
                case 'auto':
                    if (in_array(auth()->user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray()))
                        return true;
                    break;
                case 'open':
                    if (in_array(auth()->user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray())) {
                        if(in_array(auth()->user()->group->id , $this->group->pluck('id')->toArray())) {
                            return true;
                        }
                    }

                    break;
                case 'managed':
                    if (in_array(auth()->user()->group->main_character->corporation_id , $this->corporation->pluck('corporation_id')->toArray())) {
                        if (in_array(auth()->user()->group->id , $this->member->map(function($group) { return $group->id; })->toArray())) {
                            return true;
                        }
                    }

                    break;
            }

            return false;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
