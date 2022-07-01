<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveAttendanceRegistrationRequest extends FormRequest
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
            "location_id" => "required"
        ];
    }
    public function messages()
    {
        return [
          "location_id.required" => "موقعیت مکانی شما برای ثبت ورود مجدد مشخص نمی باشد"
        ];
    }
}
