<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 27.06.2018
 * Time: 22:13.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSeatGroupRequest extends FormRequest
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
        ];
    }
}
