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

    public function isManager(Group $group)
    {

        if (in_array($group->id, $this->manager->pluck('id')->toArray()))
            return true;

        return false;
    }

    /**
     * This method shall only be used from frontend and will return a boolean if the user
     * is allowed to see the seatgroup.
     *
     * @return bool
     */
    public function isAllowedToSeeSeatGroup()
    {

        if (auth()->user()->hasSuperUser() || auth()->user()->hasRole('seatgroups.edit'))
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

        //TODO: Check this logic

        if (in_array($group->id, $this->member->pluck('id')->toArray()))
            return true;

        return false;


        /*try {

            switch ($this->type) {

                case 'auto':
                    if ($this->isQualified($group))
                        return true;
                    break;
                case 'open':
                case 'managed':
                case 'hidden':
                    if (in_array($group->id, $this->member->pluck('id')->toArray()))
                        return true;
                    break;
            }

            return false;
        } catch (\Exception $e) {
            return $e;
        }
    }*/

    public function isQualified(Group $group)
    {

        $action = new GetCurrentAffiliationAction;
        if ($this->all_coporation)
            return true;

        $affiliations = collect($action->execute(['seatgroup_id' => $this->id]));
        $main_character = $group->main_character;

        $affiliations = $affiliations->filter(function ($affiliation) use ($main_character) {

            if (isset($affiliation['corporation_title'])) {
                //Handle Corp_title
                // First check if corporation is equal to main_character corporation.
                if ($affiliation['corporation_id'] === $main_character->corporation_id) {
                    //Then check if tite_id is within main_characters titles
                    if (in_array($affiliation['corporation_title']['title_id'], $main_character->titles->pluck('title_id')->toArray())) {

                        return true;
                    }
                }
            }

            //Check if main_character is an affiliated corporation
            if ($affiliation['corporation_id'] === $main_character->corporation_id && ! isset($affiliation['corporation_title'])) {

                return true;
            }

        });


        if ($affiliations->isNotEmpty())
            return true;

        return false;

    }

    public function children() //TODO: check if we can find a better name. This relates to parent-child relationship
    {
        return $this->belongsToMany(Seatgroup::class, 'seatgroup_seatgroups','parent_id','child_id');
    }
}
