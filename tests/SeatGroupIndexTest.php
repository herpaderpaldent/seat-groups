<?php

namespace Herpaderpaldent\Seat\SeatGroups\Test;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeatGroupIndexTest extends TestCase
{

    /** @test */
    public function redirectsToLoginIfUnauthorized()
    {
        $response = $this->get(route('seatgroups.index'));

        $response->assertRedirect('auth/login');
    }
}
