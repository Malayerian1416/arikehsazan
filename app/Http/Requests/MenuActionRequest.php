<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuActionRequest extends FormRequest
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
            "action" => "required|max:100"
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام الزامی می باشد.",
            "name.max" => "حداکثر طول نام 500 کاراکتر می باشد.",
            "action.required" => "درج برچسب الزامی می باشد.",
            "action.max" => "حداکثر طول برچسب 100 کاراکتر می باشد."
        ];
    }
}
