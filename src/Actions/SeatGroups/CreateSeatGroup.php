<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 22.07.2018
 * Time: 10:27
 */

namespace Herpaderpaldent\Seat\SeatGroups\Actions\SeatGroups;


use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;

class CreateSeatGroup
{
    public function execute (array $data) : Seatgroup
    {
        //dd($data);
        $seat_group = new Seatgroup;
        $seat_group->name = $data['name'];
        $seat_group->description = $data['description'];
        $seat_group->type = $data['type'];
        $seat_group->save();

        if(isset($data['roles']))
        $seat_group->role()->sync($data['roles']);

        return $seat_group;
    }

}