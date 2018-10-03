<?php
/**
 * Created by PhpStorm.
 * User: fehu
 * Date: 10.09.18
 * Time: 15:18.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager;

use Illuminate\Foundation\Http\FormRequest;

class AddManagerRequest extends FormRequest
{
    /**
     * Authorize the request by default.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'seatgroup_id'=>'required',
            'groups' => 'required_if:seatgroups,""',
            'seatgroups' => 'required_if:groups,""',
        ];
    }
}
