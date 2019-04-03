<?php

namespace Herpaderpaldent\Seat\SeatGroups\Test;


use Seat\Web\Models\User;

class UserGroupTest extends TestCase
{
    public function testGrouphasThreeMembers()
    {
        $users = $this->group->users();

        $this->assertEquals(3, $users->count());
    }

    public function testOnlyTestUserHasRefreshToken()
    {

        $user_with_refresh_token = $this->group->users->filter(function ($user) {
            return !empty($user->refresh_token);
        });

        $this->assertEquals(1, $user_with_refresh_token->count());

        $this->assertEquals($this->test_user->id, $user_with_refresh_token->first()->id);
    }

}
