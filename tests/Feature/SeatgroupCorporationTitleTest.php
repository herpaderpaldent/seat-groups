<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.10.2018
 * Time: 13:57
 */

namespace Herpaderpaldent\Seat\SeatGroups\Test\Feature;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;


class SeatgroupCorporationTitleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSeatGroupCreation()
    {
        $seatgroup = factory(Seatgroup::class)->create([
            'name' => 'TestSeatGroup'
        ]);


        $this->assertEquals('TestSeatGroup',$seatgroup->name);
    }

}