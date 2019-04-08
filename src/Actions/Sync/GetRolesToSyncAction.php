<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Sync;

use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Illuminate\Support\Collection;
use Seat\Web\Models\Group;

class GetRolesToSyncAction
{
    protected $group;

    protected $roles;

    public function __construct()
    {
        $this->roles = collect();
    }

    public function execute(Group $group) : Collection
    {

        $this->group = $group;

        SeatGroup::all()->each(function ($seat_group) {

            if ($seat_group->isQualified($this->group)) {
                switch ($seat_group->type) {
                    case 'auto':
                        foreach ($seat_group->role as $role) {
                            $this->roles->push($role->id);
                        }
                        if (! in_array($this->group->id, $seat_group->group->pluck('id')->toArray())) {
                            // add user_group to seat_group as member if no member yet.
                            $seat_group->member()->attach($this->group->id);
                        }
                        break;
                    case 'open':
                    case 'managed':
                    case 'hidden':
                        // check if user is in the group
                        if ($seat_group->isMember($this->group)) {
                            foreach ($seat_group->role as $role) {
                                $this->roles->push($role->id);
                            }
                        }
                        break;
                }
            } elseif (in_array($this->group->id, $seat_group->group->pluck('id')->toArray())) {
                $seat_group->member()->detach($this->group->id);
            }
        });

        return $this->roles;
    }
}
