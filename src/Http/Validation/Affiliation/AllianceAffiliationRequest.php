<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 10.10.2018
 * Time: 18:48.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation;

use Illuminate\Foundation\Http\FormRequest;

class AllianceAffiliationRequest extends FormRequest
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
            'alliance_ids'=>'required',
            'seatgroup_id'=>'required',
        ];
    }
}
