<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 24.06.2018
 * Time: 11:42
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation;

use Illuminate\Foundation\Http\FormRequest;

class RemoveCorporationAffiliationRequest extends FormRequest
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
            'seatgroup_id' => 'required',
            'corporation_id'   => 'required',
        ];
    }
}