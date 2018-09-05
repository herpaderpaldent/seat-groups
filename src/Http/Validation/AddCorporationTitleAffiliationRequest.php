<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 05.09.2018
 * Time: 15:20
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation;


use Illuminate\Foundation\Http\FormRequest;

class AddCorporationTitleAffiliationRequest extends FormRequest
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
            'seatgroup-corporation-id'=>'required',
            'seatgroup_id'=>'required',
            'seatgroup-title-id' => 'required'
        ];
    }

}