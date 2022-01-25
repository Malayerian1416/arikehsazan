<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            "role_menu" => "required",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام سمت الزامی می باشد.",
            "name.max" => "طول نام سمت حداکثر 500 کاراکتر است.",
            "role_menu.required" => "انتخاب حداقل یک عنوان منو برای سمت الزامی می باشد.",
        ];
    }
}
