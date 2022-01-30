<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicePaymentRequest extends FormRequest
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
            "total_amount_payed" => "required|numeric|min:1",
            "deposit_kind" => "required",
            "payment_receipt_number" => "required",
            "bank_account" => "required|numeric",
            "contractor_bank" => "required_if:deposit_kind,card,account,sheba",
            "check_id" => "required_if:deposit_kind,check",
            "check_date" => "required_if:deposit_kind,check",
            "check_number" => "required_if:deposit_kind,check",
        ];
    }
    public function messages()
    {
        return [
            "total_amount_payed.required" => "مبلغ پرداخت شده باید بزرگتر از 0 باشد.",
            "total_amount_payed.numeric" => "مبلغ پرداخت شده در فرمت عددی نمی باشد.",
            "total_amount_payed.min" => "مبلغ پرداخت شده باید بزرگتر از 0 باشد.",
            "deposit_kind.required" => "نوع واریز وجه مشخص نمی باشد.",
            "payment_receipt_number.required" => "درج شماره پیگیری رسید پرداخت نقدی،چک و یا واریزی الزامی می باشد.",
            "bank_account.required" => "انتخاب بانک مبداء جهت برداشت مبلغ پرداختی الزامی می باشد.",
            "bank_account.numeric" => "بانک مبداء انتخاب شده صحیح نمی باشد.",
            "contractor_bank.required_if" => "انتخاب حساب بانکی پیمانکار در پرداخت غیر نقدی و غیر چکی الزامی می باشد.",
            "check_id.required_if" => "انتخاب دسته چک در پرداخت با چک الزامی می باشد.",
            "check_date.required_if" => "انتخاب تاریخ وصول در پرداخت با چک الزامی می باشد.",
            "check_number.required_if" => "درج شماره برگه چک در پرداخت با چک الزامی می باشد.",
        ];
    }
}
