<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

class AddCorporationAffiliationActionTest extends TestCase
{

    public function testExecute()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'all_corporations' => false,
        ]);

        /*$corporation = factory(CorporationInfo::class)->create([
            'corporation_id' => $this->test_user->character->corporation_id
        ]);*/

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id]
        ];

        // Assert if Action runs
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // Assert if corporation is affiliated
        $this->assertTrue(in_array($this->test_corporation->corporation_id,$seatgroup->corporation->pluck('corporation_id')->toArray()));
    }

    /**
     * @test
     */
    public function createAllCorporationAffiliation()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => ['-1']
        ];

        // Assert if Action runs
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // Assert if seatgroup is all_corporation
        $this->assertTrue(SeatGroup::find($seatgroup->id)->all_corporations ? true : false);
    }

    /**
     * @test
     */
    public function createAllCorporationAndOtherCorporationAffiliation()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'all_corporations' => false,
        ]);

        /*$corporation = factory(CorporationInfo::class)->create([
            'corporation_id' => $this->test_user->character->corporation_id
        ]);*/

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => ['-1', $this->test_corporation->corporation_id]
        ];

        // Assert if Action runs
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // Assert if seatgroup is all_corporation
        $this->assertTrue(SeatGroup::find($seatgroup->id)->all_corporations ? true : false);

        // Assert if corporation is affiliated
        $this->assertTrue(in_array($this->test_corporation->corporation_id,$seatgroup->corporation->pluck('corporation_id')->toArray()));
    }

    /**
     * @test
     */
    public function createEmptyCorporationAffiliation()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => []
        ];

        // Assert if Action runs
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        // Assert if seatgroup is all_corporation
        $this->assertFalse(SeatGroup::find($seatgroup->id)->all_corporations ? true : false);

        // Assert if corporation affiliation is empty
        $this->assertTrue($seatgroup->corporation->isEmpty());
    }

    /**
     * @test
     */
    public function createInvalidCorporationAffiliation()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => 1337,
            'corporation_ids' => []
        ];

        // Assert if Action fails
        $this->assertFalse((new AddCorporationAffiliationAction)->execute($data));

        // Assert if seatgroup is all_corporation
        $this->assertFalse(SeatGroup::find($seatgroup->id)->all_corporations ? true : false);

        // Assert if corporation affiliation is empty
        $this->assertTrue($seatgroup->corporation->isEmpty());
    }

}