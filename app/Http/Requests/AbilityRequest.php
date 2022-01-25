<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbilityRequest extends FormRequest
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
            "name" => "required|max:500",
            "ability_category_id" => "required|regex:/^[1-9][0-9]*$/",
            "command" => "required|max:500"
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج عنوان دسترسی الزامی می باشد.",
            "name.max" => "طول عنوان دسترسی حداکثر 500 کاراکتر قابل قبول است.",
            "ability_category_id.required" => "عنوان سرفصل دسترسی انتخاب نشده است",
            "ability_category_id.regex" => "عنوان سرفصل دسترسی انتخاب نشده است",
            "command.required" => "درج عنوان فرمان الزامی می باشد.",
            "command.max" => "طول فرمان حداکثر 500 کاراکتر قابل قبول است."
        ];
    }
}
