<?php

namespace Herpaderpaldent\Seat\SeatGroups\Actions\Corporations;

use Herpaderpaldent\Seat\SeatGroups\Http\Validation\RemoveAffiliation;
use Herpaderpaldent\Seat\SeatGroups\Models\Seatgroup;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Corporation\Corporation;


class AddCorpAffiliation
{
    use Corporation;
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function execute(array $data)
    {
        //dd($data);
        $seat_group_id = $data['seatgroup_id'];
        $corporations = $data['corporations'];

        $seat_group = Seatgroup::find($seat_group_id);

        if(in_array("1337",$corporations)){
            $seat_group->all_corporations = true;
            $seat_group->save();
            $corporations = array_filter($corporations,function($value){
                return $value !== "1337";
            });
        }



        $seat_group->corporation()->attach($corporations);

        return true;

    }

}