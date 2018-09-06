<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;


use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\GetCurrentAffiliationAction;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationTitle;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Models\Group;
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

    public function corporationTitles()
    {

        return $this->hasMany('Herpaderpaldent\Seat\SeatGroups\Models\CorporationTitleSeatgroups', 'seatgroup_id');
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

    public function isManager(Group $group)
    {
        if (in_array($group->id , $this->manager->pluck('id')->toArray()) || auth()->user()->hasSuperUser())
            return true;

        return false;
    }

    public function isAllowedToSeeSeatGroup()
    {

        if (auth()->user()->hasSuperUser() || auth()->user()->hasRole('seatgroups.edit'))
            return true;

        return $this->isQualified(auth()->user()->group);

    }

    public function onWaitlist()
    {
        return in_array(auth()->user()->group->id , $this->waitlist->map(function($group) { return $group->id; })->toArray());
    }

    public function isMember(Group $group)
    {

        try {
            switch ($this->type) {

                case 'open':
                    if (in_array($group->id, $this->group->pluck('id')->toArray()))
                        return true;

                    break;
                case 'managed':
                    if (in_array($group->id, $this->member->pluck('id')->toArray()))
                        return true;

                    break;
                case 'hidden': //TODO resolve this

            }

            return false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function isQualified(Group $group)
    {
        $action = new GetCurrentAffiliationAction;
        if($this->all_coporation)
            return true;

        $affiliations = collect($action->execute(['seatgroup_id' => $this->id]));
        $main_character = $group->main_character;

        $affiliations = $affiliations->filter(function ($affiliation) use ($main_character) {

            if(isset($affiliation['corporation_title'])){
                //Handle Corp_title
                // First check if corporation is equal to main_character corporation.
                if($affiliation['corporation_id'] === $main_character->corporation_id){
                    //Then check if tite_id is within main_characters titles
                   if(in_array($affiliation['corporation_title']['title_id'],$main_character->titles->pluck('title_id')->toArray())){

                       return true;
                   }
                }
            }

            //Check if main_character is an affiliated corporation
            if($affiliation['corporation_id'] === $main_character->corporation_id && !isset($affiliation['corporation_title'])){

                return true;
            }

        });


        if($affiliations->isNotEmpty())
            return true;

        return false;

    }
}
