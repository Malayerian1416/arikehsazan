<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerPaymentRequest extends FormRequest
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
            "project_id" => "required|numeric",
            "contractor_id" => "required|numeric",
            "amount" => "required|numeric",
            "description" => "sometimes|nullable"
        ];
    }
    public function messages()
    {
        return [
            "project_id.required" => "انتخاب پروژه الزامی می باشد.",
            "project_id.numeric" => "انتخاب پروژه الزامی می باشد.",
            "contractor_id.required" => "انتخاب کارگر الزامی می باشد.",
            "contractor.numeric" => "انتخاب کارگر الزامی می باشد.",
            "amount.required" => "درج مبلغ پرداختنی الزامی می باشد.",
            "amount.numeric" => "درج مبلغ پرداختنی الزامی می باشد.",
        ];
    }
}
