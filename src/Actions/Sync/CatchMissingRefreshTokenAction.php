<?php


namespace Herpaderpaldent\Seat\SeatGroups\Actions\Sync;


use Herpaderpaldent\Seat\SeatGroups\Actions\Seat\GetMainCharacterAction;
use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Seat\Web\Models\Group;

class CatchMissingRefreshTokenAction
{
    protected $group;

    protected $get_main_character_action;

    protected $roles_to_temporary_remove;

    protected $has_missing_refresh_token;

    protected $superuser_roles;

    public function __construct()
    {
        $this->get_main_character_action = new GetMainCharacterAction;
        $this->roles_to_temporary_remove = collect();
        $this->superuser_roles = collect();

    }

    public function execute(Group $group)
    {
        $this->group = $group;
        $main_character = $this->get_main_character_action->execute($this->group);
        $this->has_missing_refresh_token = false;

        foreach ($this->group->users as $user) {

            //If user is deactivated skip the refresh_token check
            if (! $user->active)
                continue;

            // If a RefreshToken is missing
            if (is_null($user->refresh_token)) {

                // Throw missing refresh token event
                event(new MissingRefreshToken($user, $main_character));

                $this->has_missing_refresh_token = true;
            }

        }

        if($this->has_missing_refresh_token) {
            SeatGroup::all()->each(function ($seatgroup) {

                // If group is member and no longer qualified
                if (!$seatgroup->isQualified($this->group) && $seatgroup->isMember($this->group)) {

                    // remove member status
                    $seatgroup->member()->detach($this->group->id);
                }

                // if group is member and still qualified
                if ($seatgroup->isQualified($this->group) && $seatgroup->isMember($this->group)) {

                    // add role id's to roles_to_temporary_remove
                    $seatgroup->role->each(function ($role) {
                        $this->roles_to_temporary_remove->push($role->id);
                    });
                }

            });
        }

        $this->superuser_roles = (new CatchSuperuserAction)->execute($this->group);

        return $this->roles_to_temporary_remove
            ->reject(function ($role) {
                return in_array($role, $this->superuser_roles->toArray());
            });
    }

}