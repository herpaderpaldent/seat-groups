<?php


namespace Herpaderpaldent\Seat\SeatGroups\Actions\Sync;


use Seat\Web\Models\Group;

class CatchSuperuserAction
{
    protected $group;

    protected $roles;

    public function __construct()
    {
        $this->roles = collect();
    }

    public function execute(Group $group)
    {
        $this->group = $group;

        //Catch superuser permissions
        foreach ($this->group->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->title === 'superuser') {
                    $this->roles->push($role->id);
                }
            }
        }

        return $this->roles;
    }

}