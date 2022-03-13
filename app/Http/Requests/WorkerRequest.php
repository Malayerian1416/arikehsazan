<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
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
        if ($this->method() == "POST") {
            return [
                "name" => "required|max:500",
                "type" => "required|digits:1",
                "father_name" => "sometimes|nullable|max:150",
                "birth_date" => "sometimes|nullable|max:100|jdate:Y/m/d",
                "national_code" => "required",
                "identify_number" => "sometimes|nullable|numeric",
                "tel" => "sometimes|nullable|digits_between:11,14",
                "cellphone" => ["required", "regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/", "unique:contractors"],
                "address" => "sometimes|nullable|max:500"
            ];
        }
        elseif ($this->method() == "PUT"){
            return [
                "name" => "required|max:500",
                "type" => "required|digits:1",
                "father_name" => "sometimes|nullable|max:150",
                "birth_date" => "sometimes|nullable|max:100|jdate:Y/m/d",
                "national_code" => "required",
                "identify_number" => "sometimes|nullable|numeric",
                "tel" => "sometimes|nullable|digits_between:11,14",
                "cellphone" => ["required", "regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/", "unique:contractors,cellphone,".$this->route('Contractor').",id"],
                "address" => "sometimes|nullable|max:500"
            ];
        }
    }
    public function messages(): array
    {
        return [
            "name.required" => "درج نام پیمانکار الزامی می باشد.",
            "type.required" => "انتخاب نوع پیمانکار الزامی می باشد.",
            "type.digits" => "نوع پیمانکار عددی نمی باشد.",
            "name.max" => "طول نام پیمانکار حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "father_name.max" => "طول نام پدر حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "birth_date.max" => "طول تاریخ تولد ثبت شده حداکثر 100 کاراکتر قابل قبول است.",
            "birth_date.jdate" => "تاریخ تولد درج شده در فرمت صحیح نمی باشد.",
            "national_code.required" => "درج کد ملی پیمانکار الزامی می باشد.",
            "identify_number.numeric" => "شماره شناسنامه مقدار عددی نمی باشد.",
            "tel.digits_between" => "شماره تلفن باید بین 11 تا 14 رقم عددی باشد.",
            "cellphone.required" => "درج شماره تلفن همراه الزامی می باشد.",
            "cellphone.regex" => "فرمت شماره تلفن همراه صحیح نمی باشد.",
            "cellphone.unique" => "شماره تلفن همراه درج شده تکراری می باشد.",
            "address.max" => "طول آدرس حداکثر 500 کاراکتر قابل قبول است."
        ];
    }
}
