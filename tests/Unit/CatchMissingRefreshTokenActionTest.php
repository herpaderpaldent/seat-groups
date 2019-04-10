<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\Sync\CatchMissingRefreshTokenAction;
use Herpaderpaldent\Seat\SeatGroups\Events\MissingRefreshToken;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Seat\Web\Models\Acl\Role;

class CatchMissingRefreshTokenActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getMissingRefreshTokenEvents()
    {
        Event::fake();

        $catch_missing_refresh_token_action = new CatchMissingRefreshTokenAction;

        $catch_missing_refresh_token_action->execute($this->group);

        Event::assertDispatched(MissingRefreshToken::class, function ($event) {
            return in_array($event->user->character_id, $this->group->users->pluck('id')->toArray());
        });
    }

    /**
     * @test
     */
    public function detachMember()
    {
        $this->refreshDatabase();

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

        // attach group as member
        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        // assert if group is member
        $this->assertTrue($seatgroup->isMember($this->group));

        // secondly, prepare data for affiliation
        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id + 1 ]
        ];

        // assert if affiliation action fails
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // assert if group is not qualified
        $this->assertFalse(SeatGroup::find($seatgroup->id)->isQualified($this->group));

        // lastly catch missing refresh token
        $catch_missing_refresh_token_action = new CatchMissingRefreshTokenAction;

        $roles_to_temporary_remove = $catch_missing_refresh_token_action->execute($this->group);

        // assert that the role should not temporary be removed
        $this->assertFalse(in_array($role->id, $roles_to_temporary_remove->toArray()));

        // assert if group is no longer member
        $this->assertFalse(SeatGroup::find($seatgroup->id)->isMember($this->group));

    }


}