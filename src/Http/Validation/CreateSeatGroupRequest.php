<?php
/**
 * Created by PhpStorm.
 *  * User: Herpaderp Aldent
 * Date: 22.07.2018
 * Time: 10:21
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation;


use Illuminate\Foundation\Http\FormRequest;

class CreateSeatGroupRequest extends FormRequest
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
            'name'        => 'required|min:5',
            'description' => 'required|min:10',
            'type'        => 'required',
            'roles.*'     => 'numeric'
        ];
    }
}