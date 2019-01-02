<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 02.01.2019
 * Time: 12:03
 */

namespace Herpaderpaldent\Seat\SeatGroups\Http\Validation;


use Illuminate\Foundation\Http\FormRequest;

class AddSeatGroupNotificationSubscriptionRequest extends FormRequest
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
            'channel_id'=>'required',
            'via'=>'required',
        ];
    }

}