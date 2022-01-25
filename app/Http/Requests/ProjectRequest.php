<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => "required|max:500",
            'contract_row' => "max:500",
            'control_system' => "max:500",
            'executive_system' => "max:500",
            'contract_amount' => "required|numeric",
            'date_of_contract' => "required|jdate:Y/m/d",
            'project_start_date' => "required|jdate:Y/m/d",
            'project_completion_date' => "required|jdate:Y/m/d",
            'project_address' => "max:500",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام پروژه الزامی می باشد.",
            "name.max" => "طول نام پروژه حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "contract_row.max" => "طول ردیف قرارداد حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "control_system.max" => "طول دستگاه نظارت حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "executive_system.max" => "طول دستگاه اجرایی حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "contract_amount.required" => "درج مبلغ پیمان الزامی می باشد.",
            "contract_amount.numeric" => "مقدار مبلغ پیمان وارد شده عددی نمی باشد.",
            "date_of_contract.required" => "درج تاریخ عقد قرارداد الزامی می باشد.",
            "date_of_contract.jdate" => "تاریخ عقد قرارداد درج شده در فرمت صحیح نمی باشد.",
            "project_start_date.required" => "درج تاریخ شروع پروژه الزامی می باشد.",
            "project_start_date.jdate" => "تاریخ شروع پروژه درج شده در فرمت صحیح نمی باشد.",
            "project_completion_date.required" => "درج تاریخ پایان پروژه الزامی می باشد.",
            "project_completion_date.jdate" => "تاریخ پایان پروژه درج شده در فرمت صحیح نمی باشد.",
        ];
    }
}
