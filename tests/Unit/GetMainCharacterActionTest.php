<?php


namespace Herpaderpaldent\Seat\SeatGroups\Test\Unit;


use Herpaderpaldent\Seat\SeatGroups\Actions\Seat\GetMainCharacterAction;
use Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException;
use Herpaderpaldent\Seat\SeatGroups\Test\TestCase;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Models\UserSetting;
use Seat\Web\Models\User;

class GetMainCharacterActionTest extends TestCase
{
    /**
     * @test
     */
    public function userHasNoMainCharacterSetAndNoUsers()
    {
        $get_main_character_action = new GetMainCharacterAction();

        $this->expectException(MissingMainCharacterException::class);

        $user = factory(User::class)->create();

        $main_character = $get_main_character_action->execute($user->group);

        dd($main_character);
    }

    /**
     * @test
     * @throws \Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException
     */
    public function userHasNoMainCharacterSet()
    {
        $get_main_character_action = new GetMainCharacterAction();

        $main_character = $get_main_character_action->execute($this->group);

        $this->assertEquals($this->test_user->name, $main_character->name);
    }

    /**
     * @test
     * @throws \Herpaderpaldent\Seat\SeatGroups\Exceptions\MissingMainCharacterException
     */
    public function userHasMainCharacterSet()
    {
        $get_main_character_action = new GetMainCharacterAction();

        $user = factory(User::class)->create();

        factory(CharacterInfo::class)->create([
            'character_id' => $user->id,
            'name' => $user->name
        ]);

        factory(UserSetting::class)->create([
            'group_id' => $user->group_id,
            'name' => 'main_character_id',
            'value' => $user->id,
        ]);

        $main_character = $get_main_character_action->execute($user->group);

        $this->assertEquals($user->name, $main_character->name);
    }

}