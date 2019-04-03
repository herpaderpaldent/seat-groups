<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Web\Models\Acl\Role;

class ManagedSeatgroupTest extends TestCase
{
    public function testManagedSeatgroupExistence()
    {
        factory(Seatgroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $this->assertDatabaseHas('seatgroups', [
            'type' => 'managed'
        ]);
    }

    public function testManagedSeatgroupMembership()
    {
        $seatgroup = factory(Seatgroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $this->assertFalse($seatgroup->isMember($this->group));

        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        $this->assertTrue(Seatgroup::find($seatgroup->id)->isMember($this->group));
    }

    public function testManagedSeatgroupHasRole()
    {
        $role = factory(Role::class)->create();

        $this->assertEquals(1, $role->id);

        $seatgroup = factory(Seatgroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $seatgroup->role()->attach($role);

        $this->assertTrue(in_array($role->id, Seatgroup::find($seatgroup->id)->role()->pluck('id')->toArray()));
    }

}