<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuTitleRequest extends FormRequest
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
            "route" => "required|max:500",
            "main_route" => "required|max:500",
            "menu_header_id" => "required",
            "icon" => "sometimes|nullable"
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام منو الزامی می باشد.",
            "name.max" => "حداکثر طول نام 500 کاراکتر می باشد.",
            "route.required" => "درج مسیر الزامی می باشد.",
            "route.max" => "حداکثر طول مسیر 500 کاراکتر می باشد.",
            "main_route.required" => "درج مسیر اصلی الزامی می باشد.",
            "main_route.max" => "حداکثر طول مسیر اصلی 500 کاراکتر می باشد.",
            "menu_header_id.required" => "انتخاب گروه منو الزامی می باشد.",

        ];
    }
}
