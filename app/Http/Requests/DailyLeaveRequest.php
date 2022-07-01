<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyLeaveRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "reason" => "required",
            "selected_dates" => "required"
        ];
    }
    public function messages()
    {
        return [
            "reason.required" => "درج دلیل درخواست مرخصی الزامی می باشد.",
            "selected_dates.required" => "انتخاب حداقل یک روز برای درخواست مرخصی الزامی می باشد."
        ];
    }
}
