<?php
/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Created by PhpStorm.
 * User: felix
 * Date: 08.10.2018
 * Time: 13:57
 */

namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Corporations\AddCorporationAffiliationAction;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Eveapi\Models\Corporation\CorporationInfo;


class SeatGroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSeatGroupCreation()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'name' => 'TestSeatGroup'
        ]);

        $this->assertEquals('TestSeatGroup',$seatgroup->name);
    }

    /**
     * @test
     */
    public function isMemberFunction()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        $this->assertTrue($seatgroup->isMember($this->group));
    }

    /**
     * @test
     */
    public function isQualifiedFunction()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => true,
        ]);

        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        $this->assertTrue($seatgroup->isQualified($this->group));
    }

    /**
     * @test
     */
    public function isTestUserQualifiedFunction()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id]
        ];

        // Assert if Action fails
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        $this->assertTrue(SeatGroup::find($seatgroup->id)->isQualified($this->group));
    }

    /**
     * @test
     */
    public function isTestUserNotQualified()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $data = [
            'seatgroup_id' => $seatgroup->id,
            'corporation_ids' => [$this->test_corporation->corporation_id + 1 ]
        ];

        // Assert if Action fails
        $this->assertTrue((new AddCorporationAffiliationAction)->execute($data));

        $this->assertFalse(SeatGroup::find($seatgroup->id)->isQualified($this->group));
    }

}