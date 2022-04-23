<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => "required",
            "geoJson" => "required",
            "project_id" => "required",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام موقعین الزامی می باشد",
            "geoJson.required" => "انتخاب مختصات موقعیت الزامی می باشد",
            "project_id.required" => "انتخاب پروژه الزامی می باشد",
        ];
    }
}
