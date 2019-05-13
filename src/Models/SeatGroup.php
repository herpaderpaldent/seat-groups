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

class SeatGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seatgroups';

    protected $fillable = ['name', 'description', 'type', 'role_id'];

    public function role()
    {

        return $this->belongsToMany('Seat\Web\Models\Acl\Role', 'role_seatgroup', 'seatgroup_id');
    }

    public function group()
    {

        return $this->belongsToMany('Seat\Web\Models\Group', 'group_seatgroup', 'seatgroup_id')
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

        return $this->belongsToMany('Seat\Web\Models\Group', 'group_seatgroup', 'seatgroup_id')
            ->wherePivot('is_manager', 1);
    }

    public function member()
    {

        return $this->belongsToMany('Seat\Web\Models\Group', 'group_seatgroup', 'seatgroup_id')
            ->wherePivot('on_waitlist', 0);
    }

    public function waitlist()
    {

        return $this->belongsToMany('Seat\Web\Models\Group', 'group_seatgroup', 'seatgroup_id')
            ->wherePivot('on_waitlist', 1);
    }

    public function alliance()
    {

        return $this->belongsToMany('Seat\Eveapi\Models\Alliances\Alliance', 'alliance_seatgroup', 'seatgroup_id', 'alliance_id');
    }

    public function skills()
    {
        return $this->hasMany('Herpaderpaldent\Seat\SeatGroups\Models\SkillsSeatgroups', 'seatgroup_id');
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

        if (auth()->user()->hasSuperUser() || auth()->user()->has('seatgroups.edit', false) || $this->isManager(auth()->user()->group) || $this->isMember(auth()->user()->group))
            return true;

        if($this->type === 'hidden')
            return false;

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

        $affiliations = collect($action->execute(['seatgroup_id' => $this->id]));

        /**
         * Logic:
         *  Do I match Corporation OR Alliance IF it is set as an affiliation
         *  If a title is set, do I match ANY of the titles
         *  If there are skills, match ALL of the skills
         */

        foreach ($group->users as $user){

            if (is_null($user->refresh_token))
                continue;

            $matchCorpAlliance = true;
            $matchTitle = true;
            $matchSkills = true;

            //Check if affiliation groups exist
            foreach($affiliations as $affiliation){
                if (isset($affiliation['corporation_title']))
                    $matchTitle = false;

                if (isset($affiliation['corporation_id']) || isset($affiliation['alliance_id']))
                    $matchCorpAlliance = false;
            }

            if ($this->all_corporations)
                $matchCorpAlliance = true;

            foreach($affiliations as $affiliation){

                if (isset($affiliation['corporation_title'])) {
                    // Handle Corp_title
                    // First check if corporation is equal to character corporation.
                    if ($affiliation['corporation_id'] === $user->character->corporation_id) {
                        //Then check if tite_id is within main_characters titles
                        if (in_array($affiliation['corporation_title']['title_id'], $user->character->titles->pluck('title_id')->toArray())) {

                            $matchTitle = true;
                        }
                    }
                }

                //Check if character is an affiliated corporation
                if (isset($affiliation['corporation_id']) && $affiliation['corporation_id'] === $user->character->corporation_id && ! isset($affiliation['corporation_title']))
                    $matchCorpAlliance = true;

                //Check if character is in an affiliated alliance
                if (isset($affiliation['alliance_id']) && $affiliation['alliance_id'] === $user->character->alliance_id)
                    $matchCorpAlliance = true;

                if (isset($affiliation['skill_id'])) {
                    $found = false;
                    foreach($user->character->skills as $skill) {
                        if($skill->skill_id == $affiliation['skill_id'] && $skill->trained_skill_level >= $affiliation['skill_level'])
                            $found = true;
                    }
                    if(!$found)
                        $matchSkills = false;
                }
            }

            if($matchCorpAlliance && $matchTitle && $matchSkills)
                return true;
        }

        return false;

    }

    public function children()
    {
        return $this->belongsToMany(SeatGroup::class, 'seatgroup_seatgroups', 'parent_id', 'child_id');
    }
}
