<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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

        if ($this->isMethod("put")) {
            return [
                "quantity" => "required|numeric|min:0.1",
                "amount" => "required|numeric|min:1",
                "payment_offer" => "required|numeric|min:1",
                "payment_offer_percent" => "required|numeric|min:1",
                "extra_work_desc" => "sometimes|nullable",
                "extra_work_amount" => "sometimes|nullable",
                "deduction_work_desc" => "sometimes|nullable",
                "deduction_work_amount" => "sometimes|nullable",
                "final_invoice" => "sometimes|nullable",
                "comment" => "sometimes|nullable"
            ];
        }
        else{
            return [
                "project_id" => "required|numeric",
                "contract_id" => "required|numeric",
                "quantity" => "required|numeric|min:0.1",
                "amount" => "required|numeric|min:1",
                "payment_offer" => "required|numeric|min:1",
                "payment_offer_percent" => "required|numeric|min:1",
                "invoice_number" => "required|numeric|min:1",
                "extra_work_desc" => "sometimes|nullable",
                "extra_work_amount" => "sometimes|nullable",
                "deduction_work_desc" => "sometimes|nullable",
                "deduction_work_amount" => "sometimes|nullable",
                "final_invoice" => "sometimes|nullable",
                "comment" => "sometimes|nullable"
            ];
        }
    }
    public function messages()
    {
        return [
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "پروژه انتخاب شده معتبر نمی باشد.",
            "contract_id.required" => "انتخاب پیمان الزامی می باشد.",
            "contract_id.numeric" => "پیمان انتخاب شده معتبر نمی باشد.",
            "quantity.required" => "مقدار کارکرد باید بزرگتر از صفر باشد.",
            "quantity.numeric" => "مقدار کارکرد باید بزرگتر از صفر باشد.",
            "quantity.min" => "مقدار کارکرد باید بزرگتر از صفر باشد.",
            "amount.required" => "مقدار بهاء جزء باید بزرگتر از صفر باشد.",
            "amount.numeric" => "مقدار بهاء جزء باید بزرگتر از صفر باشد.",
            "payment_offer.min" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "payment_offer.required" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "payment_offer.numeric" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "payment_offer_percent.min" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "payment_offer_percent.required" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "payment_offer_percent.numeric" => "مقدار پیشنهاد پرداخت باید بزرگتر از صفر باشد.",
            "amount.min" => "مقدار بهاء جزء باید بزرگتر از صفر باشد.",
            "invoice_number.required" => "مقدار شماره وضعیت باید بزرگتر از صفر باشد.",
            "invoice_number.numeric" => "مقدار شماره وضعیت باید بزرگتر از صفر باشد.",
            "invoice_number.min" => "مقدار شماره وضعیت باید بزرگتر از صفر باشد.",
        ];
    }
}
