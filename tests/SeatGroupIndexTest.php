<?php

namespace Herpaderpaldent\Seat\SeatGroups\Test;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Seat\Services\Models\UserSetting;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;

class SeatGroupIndexTest extends TestCase
{

    /** @test */
    public function redirectsToLoginIfUnauthorized()
    {
        $response = $this->get(route('seatgroups.index'));

        $response->assertRedirect('auth/login');
    }

    /** @test */
    public function assertUnauthorizedUser()
    {
        $response = $this->actingAs($this->test_user)
            ->get(route('seatgroups.index'));

        $response->assertRedirect('auth/unauthorized');
    }

    /** @test */
    public function testUserWithPermissionSeesIndex()
    {

        $this->giveTestUserPermission('seatgroups.view');
        $this->setMainCharacter();

        // Change path.public from Laravel IoC Container to point to proper laravel mix manifest.
        //$this->app->instance('path.public', __DIR__ .'/vendor/src/public');

        $response = $this->actingAs($this->test_user)
            ->get(route('seatgroups.index'));
            //->get(route('home'));

        //$response->assertSee('Home');

        $response->assertSee('SeAT Groups');
        //$response->assertViewIs('seatgroups::index');
    }

    private function giveTestUserPermission(string $permission_name)
    {
        // TODO: Check if AccessManager Trait could be reused
        // Create a role
        $role = factory(Role::class)->create();

        // attach seatgroups.view permission to role
        $permission = Permission::firstOrNew([
            'title' => $permission_name
        ]);

        $role->permissions()->save($permission);

        // give test_user role with permission
        $role->groups()->save($this->group);

        $helper_collection = collect();

        foreach ($this->group->roles as $role) {
            foreach ($role->permissions as $permission) {
                $helper_collection->push($permission->title);
            }
        }

        $this->assertTrue(in_array($permission_name, $helper_collection->toArray()));
    }

    private function setMainCharacter()
    {
        // set main_character
        factory(UserSetting::class)->create([
            'group_id' => $this->group->id,
            'name' => 'main_character_id',
            'value' => $this->test_user->id,
        ]);

        $this->assertNotNull($this->group->main_character);
    }
}
