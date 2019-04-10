<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Web\Models\Acl\Role;

class ManagedSeatgroupTest extends TestCase
{
    public function testManagedSeatgroupExistence()
    {
        factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $this->assertDatabaseHas('seatgroups', [
            'type' => 'managed'
        ]);
    }

    public function testManagedSeatgroupMembership()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $this->assertFalse($seatgroup->isMember($this->group));

        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        $this->assertTrue(SeatGroup::find($seatgroup->id)->isMember($this->group));
    }

    public function testManagedSeatgroupHasRole()
    {
        $role = factory(Role::class)->create();

        $this->assertEquals(1, $role->id);

        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $seatgroup->role()->attach($role);

        $this->assertTrue(in_array($role->id, SeatGroup::find($seatgroup->id)->role()->pluck('id')->toArray()));
    }

}