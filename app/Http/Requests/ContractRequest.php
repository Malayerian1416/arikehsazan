<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
            'project_id' => "required|regex:/^[1-9][0-9]*$/",
            "contract_branch_id" => "required|regex:/^[1-9][0-9]*$/",
            "contract_category_id" => "required|regex:/^[1-9][0-9]*$/",
            "contractor_id" => "required|regex:/^[1-9][0-9]*$/",
            'name' => "required|max:500",
            'contract_row' => "max:500",
            'amount' => "required|numeric",
            "unit_id" => "required|regex:/^[1-9][0-9]*$/",
            'date_of_contract' => "required|jdate:Y/m/d",
            'contract_start_date' => "required|jdate:Y/m/d",
            'contract_completion_date' => "required|jdate:Y/m/d",
        ];
    }
    public function messages()
    {
        return [
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.regex" => "پروژه ای انتخاب نشده است",
            "contract_branch_id.required" => "انتخاب رشته پیمان الزامی می باشد.",
            "contract_branch_id.regex" => "رشته پیمانی انتخاب نشده است",
            "contract_category_id.required" => "انتخاب سرفصل پیمان الزامی می باشد.",
            "contract_category_id.regex" => "سرفصل پیمانی انتخاب نشده است",
            "contractor_id.required" => "انتخاب پیمانکار الزامی می باشد.",
            "contractor_id.regex" => "پیمانکاری انتخاب نشده است.",
            "name.required" => "درج نام پیمان الزامی می باشد.",
            "name.max" => "طول نام پیمان حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "contract_row.max" => "طول ردیف قرارداد حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "amount.required" => "درج مبلغ پیمان الزامی می باشد.",
            "amount.numeric" => "مقدار مبلغ پیمان وارد شده عددی نمی باشد.",
            "unit_id.required" => "انتخاب واحد شمارش الزامی می باشد.",
            "unit_id.regex" => "واحد شمارشی انتخاب نشده است.",
            "date_of_contract.required" => "درج تاریخ عقد قرارداد الزامی می باشد.",
            "date_of_contract.jdate" => "تاریخ عقد قرارداد درج شده در فرمت صحیح نمی باشد.",
            "contract_start_date.required" => "درج تاریخ شروع پیمان الزامی می باشد.",
            "contract_start_date.jdate" => "تاریخ شروع پیمان درج شده در فرمت صحیح نمی باشد.",
            "contract_completion_date.required" => "درج تاریخ پایان پیمان الزامی می باشد.",
            "contract_completion_date.jdate" => "تاریخ پایان پیمان درج شده در فرمت صحیح نمی باشد.",
        ];
    }
}
