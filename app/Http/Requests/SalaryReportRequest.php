<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryReportRequest extends FormRequest
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
            "staff_id" => "required",
            "from_date" => "required|jdate:Y/m/d",
            "to_date" => "required|jdate:Y/m/d",
            "holidays" => "sometimes|nullable",
            "work_shift_id" => "required"
        ];
    }

    public function messages()
    {
        return [
            "staff_id.required" => "انتخاب پرسنل الزامی می باشد",
            "from_date.required" => "انتخاب تاریخ ابتدا الزامی می باشد",
            "to_date.required" => "انتخاب تاریخ انتها الزامی می باشد",
            "from_date.jdate" => "تاریخ ابتدا در فرمت صحیح نمی باشد",
            "to_date.jdate" => "تاریخ انتها در فرمت صحیح نمی باشد",
            "work_shift_id.required" => "انتخاب نوبت کاری الزامی می باشد"
        ];
    }
}
