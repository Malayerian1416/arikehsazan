<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            "location_id" => "required",
            "staff_id" => "required",
            "date" => "required",
            "time" => "required",
        ];
    }
    public function messages()
    {
        return [
            "location_id.required" => "انتخاب موقعیت الزامی می باشد",
            "staff_id.required" => "انتخاب کارمند الزامی می باشد",
            "date.required" => "انتخاب تاریخ الزامی می باشد",
            "time.required" => "انتخاب زمان الزامی می باشد",
        ];
    }
}
