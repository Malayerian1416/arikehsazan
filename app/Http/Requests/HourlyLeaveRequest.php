<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HourlyLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            "reason" => "required",
            "location" => "sometimes|nullable",
            "departure" => "required_with:arrival",
            "arrival" => "required_with:departure"
        ];
    }
    public function messages()
    {
        return [
            "reason.required" => "درج علت مرخصی الزامی می باشد.",
            "departure.required_with" => "در صورت درج زمان ورود، وارد نمودن زمان خروج الزامی می باشد.",
            "arrival.required_with" => "در صورت درج زمان خروج، وارد نمودن زمان ورود الزامی می باشد."
        ];
    }
}
