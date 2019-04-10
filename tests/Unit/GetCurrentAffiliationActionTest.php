<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups\GetCurrentAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;

class GetCurrentAffiliationActionTest extends TestCase
{
    /**
     * @test
     */
    public function getEmptyAffiliations()
    {
        $get_current_affiliation_action = new GetCurrentAffiliationAction;

        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id
        ];

        $this->assertTrue($get_current_affiliation_action->execute($data)->isEmpty());
    }

    /**
     * @test
     */
    public function getNotEmptyAffiliations()
    {
        $get_current_affiliation_action = new GetCurrentAffiliationAction;

        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id]
        ];

        // Assert if Action runs
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        $data2 = [
            'seatgroup_id' => $seatgroup->id
        ];

        $this->assertTrue($get_current_affiliation_action->execute($data2)->isNotEmpty());
    }


}