<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
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
            "name" => "required|max:255",
            "branch" => "required|max:255",
            "branch_code" => "required|numeric",
            "account_number" => "required|numeric",
            "card_number" => "required|numeric",
            "sheba_number" => "required|numeric",
            "balance" => "required|numeric",
            "phone" => "sometimes|nullable",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام بانک الزامی می باشد.",
            "name.max" => "حداکثر طول نام بانک 255 کاراکتر می باشد.",
            "branch.required" => "درج نام شعبه بانک الزامی می باشد.",
            "branch.max" => "حداکثر طول نام شعبه بانک 255 کاراکتر می باشد.",
            "branch_code.required" => "درج کد شعبه الزامی می باشد.",
            "branch_code.numeric" => "کد شعبه در فرمت صحیح نمی باشد",
            "account_number.required" => "درج شماره حساب الزامی می باشد.",
            "account_number.numeric" => "شماره حساب در فرمت صحیح نمی باشد",
            "card_number.required" => "درج شماره کارت الزامی می باشد.",
            "card_number.numeric" => "شماره کارت در فرمت صحیح نمی باشد",
            "sheba_number.required" => "درج شماره شبا الزامی می باشد.",
            "sheba_number.numeric" => "شماره شبا در فرمت صحیح نمی باشد",
            "balance.required" => "درج موجودی اولیه الزامی می باشد.",
            "balance.numeric" => "موجودی اولیه در فرمت صحیح نمی باشد",

        ];
    }
}
