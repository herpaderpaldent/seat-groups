<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\GetRolesToSyncAction;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Web\Models\Acl\Role;

class GetRolesToSyncActionTest extends TestCase
{
    /**
     * @test
     */
    public function getRole()
    {
        // First create a seatgroup
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        // add a role to the seat-group
        $role = factory(Role::class)->create();

        $seatgroup->role()->attach($role);

        // Test that seatgroup has role
        $this->assertTrue(in_array($role->id, SeatGroup::find($seatgroup->id)->role()->pluck('id')->toArray()));

        // secondly attach group as member
        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        // assert if group is member
        $this->assertTrue($seatgroup->isMember($this->group));

        // thirdly prepare data for affiliation
        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id]
        ];

        // assert if affiliation action fails
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // assert if group is qualified
        $this->assertTrue(SeatGroup::find($seatgroup->id)->isQualified($this->group));

        $roles = (new GetRolesToSyncAction)->execute($this->group);

        $this->assertTrue(in_array($role->id, $roles->toArray()));
    }

}