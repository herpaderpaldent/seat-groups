<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\CatchSuperuserAction;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\Role;

class CatchSuperuserActionTest extends TestCase
{
    use AccessManager;
    /**
     * @test
     */
    public function getSuperuserRole()
    {
        // First create a seatgroup
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        // add a role to the seat-group
        $role = factory(Role::class)->create();

        $this->giveGroupRole($this->group->id, $role->id);
        $this->giveRolePermission($role->id, 'superuser', false);

        // assert that test user got superuser permission
        $this->assertTrue($this->test_user->has('superuser'));

        $roles = (new CatchSuperuserAction)->execute($this->group);

        // assert if returned roles contain the created role
        $this->assertTrue(in_array($role->id, $roles->toArray()));
    }

}