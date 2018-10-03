<?php
/**
 * Created by PhpStorm.
 * User: fehu
 * Date: 11.09.18
 * Time: 15:44.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Manager;

use Illuminate\Foundation\Http\FormRequest;

class RemoveManagerRequest extends FormRequest
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
            'group_id' => 'required_if:children_id,""',
            'children_id' => 'required_if:group_id,""',
        ];
    }
}
