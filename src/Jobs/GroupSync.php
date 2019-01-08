<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 01.09.2018
 * Time: 16:16.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Jobs;

use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatgroupLog;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroupNotification;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupErrorNotification;
use Herpaderpaldent\Seat\SeatGroups\Notifications\SeatGroupUpdateNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

class GroupSync extends SeatGroupsJobBase
{
    /**
     * @var array
     */
    protected $tags = ['sync'];

    /**
     * @var \Seat\Web\Models\Group
     */
    private $group;

    /**
     * @var \Seat\Eveapi\Models\Character\CharacterInfo
     */
    private $main_character;

    /**
     * @var \Illuminate\Support\Collection;
     */
    private $roles;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * ConversationOrchestrator constructor.
     *
     * @param \Seat\Web\Models\Group $group
     */
    public function __construct(Group $group)
    {

        $this->group = $group;
        $this->main_character = $group->main_character;
        if (is_null($group->main_character)) {
            logger()->warning('Group has no main character set. Attempt to make assignation based on first attached character.', [
                'group_id' => $group->id,
            ]);
            $this->main_character = $group->users->first()->character;
        }

        // avoid the construct to throw an exception if no character has been set
        if (! is_null($this->main_character)) {
            logger()->debug('Initialising SeAT Group sync for ' . $this->main_character->name);

            array_push($this->tags, sprintf('users: %s',
                $this->group->users->map(function ($user) { return $user->name; })->implode(', ')));
        }

        $this->roles = collect();

    }

    public function handle()
    {

        var_dump('test');

        // in case no main character has been set, throw an exception and abort the process
        if (is_null($this->main_character))
            throw new MissingMainCharacterException($this->group);

        Redis::funnel('seat-groups:jobs.group_sync_' . $this->group->id)->limit(1)->then(function () {

            try {

                $this->beforeStart();

                Seatgroup::all()->each(function ($seat_group) {

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

                $sync = $this->group->roles()->sync($this->roles->unique());

                $this->onFinish($sync);

                logger()->debug('Group has beend synced for ' . $this->main_character->name);

            } catch (\Throwable $exception) {

                $this->onFail($exception);

            }

        }, function () {

            logger()->warning('A GroupSync job is already running for ' . $this->main_character->name . ' Removing the job from the queue.');

            $this->delete();
        });

    }

    private function beforeStart()
    {

        //Catch superuser permissions
        foreach ($this->group->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->title === 'superuser') {
                    $this->roles->push($role->id);
                }
            }
        }

        /*
         * Check if a user is missing a refresh token
         * if is missing take away all memberships gained
         * through the missing character
         */
        $this->catchMissingRefreshToken();

    }

    private function catchMissingRefreshToken()
    {
        foreach ($this->group->users as $user) {

            //If user is deactivated skip the refresh_token check
            if (! $user->active)
                continue;

            // If a RefreshToken is missing
            if (is_null($user->refresh_token)) {
                // take away all roles
                $this->group->roles()->sync([]);
                Seatgroup::all()->each(function ($seatgroup) {

                    $seatgroup->member()->detach($this->group->id);
                });

                $message = sprintf('The RefreshToken of %s in user group of %s (%s) is missing. '
                    . 'Ask the owner of this user group to login again with this user, in order to provide a new RefreshToken. '
                    . 'This user group will lose all potentially gained roles through this character.',
                    $user->name, $this->main_character->name, $this->group->users->map(function ($user) {return $user->name; })
                        ->implode(', ')
                );

                SeatgroupLog::create([
                    'event'   => 'error',
                    'message' => $message,
                ]);

                if (! empty($this->recipients))
                    Notification::send($this->recipients, (new SeatGroupErrorNotification($user, $message)));

            }
        }
    }

    private function onFail($exception)
    {

        report($exception);

        event(new GroupSyncFailed($this->group, $this->main_character));

        throw $exception;
    }

    private function onFinish($sync)
    {

        event(new GroupSynced($this->group, $this->main_character, $sync));
    }
}
