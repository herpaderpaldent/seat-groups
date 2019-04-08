<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 01.09.2018
 * Time: 16:16.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Jobs;

use Exception;
use Herpaderpaldent\Seat\SeatGroups\Actions\Seat\GetMainCharacterAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\CatchMissingRefreshTokenAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\CatchSuperuserAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\GetRolesToSyncAction;
use Herpaderpaldent\Seat\SeatGroups\Events\GroupSynced;
use Herpaderpaldent\Seat\SeatGroups\Events\GroupSyncFailed;
use Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Illuminate\Support\Facades\Redis;
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
     * @var \Illuminate\Support\Collection;
     */
    private $roles_to_temporary_remove;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * ConversationOrchestrator constructor.
     *
     * @param \Seat\Web\Models\Group $group
     *
     * @throws \Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException
     */
    public function __construct(Group $group)
    {
        $get_main_character_action = new GetMainCharacterAction();

        $this->group = $group;
        $this->main_character = $get_main_character_action->execute($this->group);

        // avoid the construct to throw an exception if no character has been set
        if (! is_null($this->main_character)) {
            logger()->debug('Initialising SeAT Group sync for ' . $this->main_character->name);

            array_push($this->tags, sprintf('users: %s',
                $this->group->users->map(function ($user) { return $user->name; })->implode(', ')));
        }

        $this->roles = collect();
        $this->roles_to_temporary_remove = collect();
    }

    public function handle()
    {

        // in case no main character has been set, throw an exception and abort the process
        if (is_null($this->main_character))
            throw new MissingMainCharacterException($this->group);

        Redis::funnel('seat-groups:jobs.group_sync_' . $this->group->id)->limit(1)->then(function () {

            try {

                $this->beforeStart();

                $roles_to_sync = (new GetRolesToSyncAction)->execute($this->group);

                $this->roles = $this->roles->merge($roles_to_sync);

                $roles_to_sync = $this->roles->unique()->reject(function ($role) {
                    return in_array($role, $this->roles_to_temporary_remove->toArray());
                });

                $sync = $this->group->roles()->sync($roles_to_sync->toArray());

                $this->onFinish($sync);

                logger()->debug('Group has beend synced for ' . $this->main_character->name);

            } catch (Exception $exception) {

                $this->onFail($exception);
            }

        }, function () {

            logger()->warning('A GroupSync job is already running for ' . $this->main_character->name . ' Removing the job from the queue.');

            $this->delete();
        });

    }

    private function beforeStart()
    {

        $this->roles = $this->roles->merge((new CatchSuperuserAction)->execute($this->group));

        /*
         * if group is member of a group for which he still qualifies but is missing
         * add the roles which he would have gained to a temporary_remove list
         */
        $this->roles_to_temporary_remove = (new CatchMissingRefreshTokenAction)->execute($this->group);

    }

    private function onFail($exception)
    {

        report($exception);

        event(new GroupSyncFailed($this->group, $this->main_character));

        throw $exception;
    }

    private function onFinish($sync)
    {
        if (! (empty($sync['attached']) && empty($sync['detached'])))
            event(new GroupSynced($this->group, $this->main_character, $sync));
    }
}
