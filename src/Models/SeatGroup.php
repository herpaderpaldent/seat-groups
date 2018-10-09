<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 13.02.2018
 * Time: 21:43.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Models;

use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\GetCurrentAffiliationAction;
use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\Group;

class Seatgroup extends Model
{

    protected $fillable = ['name', 'description', 'type', 'role_id'];

    public function role()
    {

        return $this->belongsToMany('Seat\Web\Models\Acl\Role');
    }

    public function group()
    {

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->withPivot('is_manager', 'on_waitlist');
    }

    public function corporation()
    {

        return $this->belongsToMany('Seat\Eveapi\Models\Corporation\CorporationInfo',
            'corporation_info_seatgroup', 'seatgroup_id', 'corporation_id');
    }

    public function corporationTitles()
    {

        return $this->hasMany('Herpaderpaldent\Seat\SeatGroups\Models\CorporationTitleSeatgroups', 'seatgroup_id');
    }

    public function manager()
    {

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('is_manager', 1);
    }

    public function member()
    {

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist', 0);
    }

    public function waitlist()
    {

        return $this->belongsToMany('Seat\Web\Models\Group')
            ->wherePivot('on_waitlist', 1);
    }

    /**
     * @param \Seat\Web\Models\Group $group
     *
     * @return bool
     */
    public function isManager(Group $group)
    {

        if (in_array($group->id, $this->manager->pluck('id')->toArray()))
            return true;

        if($this->children){
            foreach ($this->children as $child) {
                if(in_array($group->id, $child->member->pluck('id')->toArray()))
                    return true;
            }
        }

        return false;
    }

    /**
     * This method shall only be used from frontend or controller and will return a boolean if the user
     * is allowed to see the seatgroup.
     *
     * @return bool
     */
    public function isAllowedToSeeSeatGroup()
    {

        if (auth()->user()->hasSuperUser() || auth()->user()->hasRole('seatgroups.edit') || $this->isManager(auth()->user()->group))
            return true;

        return $this->isQualified(auth()->user()->group);

    }

    public function onWaitlist()
    {

        return in_array(auth()->user()->group->id, $this->waitlist->map(function ($group) {

            return $group->id;
        })->toArray());
    }

    /**
     * @param \Seat\Web\Models\Group $group
     *
     * @return bool
     */
    public function isMember(Group $group)
    {

        if (in_array($group->id, $this->member->pluck('id')->toArray()))
            return true;

        return false;
    }

    public function isQualified(Group $group)
    {

        $action = new GetCurrentAffiliationAction;

        if ($this->all_corporations)
            return true;

        $affiliations = collect($action->execute(['seatgroup_id' => $this->id]));

        foreach ($group->users as $user){

            foreach($affiliations as $affiliation){

                if (isset($affiliation['corporation_title'])) {
                    // Handle Corp_title
                    // First check if corporation is equal to character corporation.
                    if ($affiliation['corporation_id'] === $user->character->corporation_id) {
                        //Then check if tite_id is within main_characters titles
                        if (in_array($affiliation['corporation_title']['title_id'], $user->character->titles->pluck('title_id')->toArray())) {

                            return true;
                        }
                    }
                }

                //Check if main_character is an affiliated corporation
                if (isset($affiliation['corporation_id']) && $affiliation['corporation_id'] === $user->character->corporation_id && ! isset($affiliation['corporation_title'])) {

                    return true;
                }

            }
        }

        return false;

    }

    public function children()
    {
        return $this->belongsToMany(Seatgroup::class, 'seatgroup_seatgroups', 'parent_id', 'child_id');
    }
}
