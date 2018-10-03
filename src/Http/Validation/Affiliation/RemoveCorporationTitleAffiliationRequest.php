<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 06.09.2018
 * Time: 09:54.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation;

use Illuminate\Foundation\Http\FormRequest;

class RemoveCorporationTitleAffiliationRequest extends FormRequest
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
            'corporation_id'=>'required',
            'seatgroup_id'=>'required',
            'title_id' => 'required',
        ];
    }
}
