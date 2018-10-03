<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 06.09.2018
 * Time: 09:08.
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation\Affiliation;

use Illuminate\Foundation\Http\FormRequest;

class RemoveAllCorporationsRequest extends FormRequest
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
