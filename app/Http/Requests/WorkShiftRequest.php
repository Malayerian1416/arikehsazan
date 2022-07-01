<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkShiftRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            "name" => "required",
            "arrival" => "required_with:departure|date_format:H:i|before:departure",
            "departure" => "required_with:arrival|date_format:H:i|after:arrival"
        ];
    }
    public function messages(): array
    {
        return [
            "name.required" => "درج عنوان الزامی می باشد",
            "arrival.required_with" => "درج ساعت شروع کار الزامی می باشد",
            "arrival.date_format" => "قالب ساعت شروع به کار صحیح نمی باشد",
            "arrival.before" => "ساعت شروع کار باید از ساعت پایان کار کوچکتر باشد",
            "departure.required_with" => "درج ساعت پایان کار الزامی می باشد",
            "departure.date_format" => "قالب ساعت پایان به کار صحیح نمی باشد",
            "departure.after" => "ساعت پایان کار باید از ساعت شروع کار بزرگتر باشد",
        ];
    }
}
