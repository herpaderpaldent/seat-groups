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

namespace Herpaderpaldent\Seat\SeatGroups\Test\Integration;

use Herpaderpaldent\Seat\SeatGroups\Jobs\GroupSync;
use Herpaderpaldent\Seat\SeatGroups\Models\SeatGroup;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Queue;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Models\UserSetting;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Group;
use Seat\Web\Models\User;

class ManagedGroupSyncTest extends TestCase
{
    use AccessManager;

    public function testManagedGroupSync()
    {
        $seatgroup = factory(SeatGroup::class)->create([
            'type' => 'managed',
            'all_corporations' => false,
        ]);

        $role = factory(Role::class)->create();

        $seatgroup->role()->attach($role);

        $seatgroup->group()->attach($this->group, [
            'on_waitlist' => 0,
        ]);

        factory(UserSetting::class)->create([
            'group_id' => $this->group->id,
            'name' => 'main_character_id',
            'value' => $this->test_user->id,
        ]);



        $this->giveGroupRole($this->group->id, $role->id);

        // Assert if group has  Role
        $this->assertTrue(in_array($role->title, $this->group->roles->pluck('title')->toArray()));

        // Assert if seat_group has role
        $this->assertTrue(in_array($role->id, SeatGroup::find($seatgroup->id)->role()->pluck('id')->toArray()));

        Queue::fake();

        $job = new GroupSync($this->group);
        $job->handle();

        /*Queue::assertPushed(GroupSync::class, function ($job) use ($role) {
            var_dump($job);
            return in_array($role->id, $job->roles->toArray());
        });*/


        //var_dump('no longer');
        // Assert if group has role no longer
        //TODO: Fix this assertion
        //$this->assertFalse(in_array($role->id, $this->group->roles->pluck('id')->toArray()));
    }

}