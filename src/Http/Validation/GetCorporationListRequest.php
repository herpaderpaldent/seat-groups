<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 17:53
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation;


use Illuminate\Foundation\Http\FormRequest;

class GetCorporationListRequest extends FormRequest
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
            'seatgroup_id'=>'required'
        ];
    }

}