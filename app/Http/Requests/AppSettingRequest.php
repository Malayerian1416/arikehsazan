<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppSettingRequest extends FormRequest
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
            "name" => "required",
            "description" => "sometimes|nullable",
            "address" => "sometimes|nullable",
            "phone" => "sometimes|nullable",
            "ceo_user_id" => "required",
            "app_ver" => "required"
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام شرکت الزامی می باشد",
            "ceo_user_id.required" => "انتخاب مدیرعامل الزامی می باشد",
            "app_ver.required" => "درج نسخه برنامه الزامی می باشد"
        ];
    }
}
