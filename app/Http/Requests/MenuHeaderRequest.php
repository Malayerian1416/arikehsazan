<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuHeaderRequest extends FormRequest
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
            "is_admin" => "sometimes|nullable",
            "name" => "required|max:255",
            "slug" => "required|max:500",
            "icon_id" => "required|max:100",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام گروه الزامی می باشد.",
            "name.max" => "حداکثر طول نام گروه 255 کاراکتر می باشد.",
            "slug.required" => "درج برچسب گروه الزامی می باشد.",
            "slug.max" => "حداکثر طول برچسب گروه 255 کاراکتر می باشد.",
            "icon_id.required" => "درج آیکون گروه الزامی می باشد.",
            "icon_id.max" => "حداکثر طول آیکون گروه 255 کاراکتر می باشد.",
        ];
    }
}
