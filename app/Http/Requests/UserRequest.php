<?php

namespace App\Http\Requests;

use App\Rules\NationalCodeChecker;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                'name' => 'required|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required',
                'email' => 'sometimes|nullable|string|email|max:255|unique:users',
                "mobile" => ["required", "regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/", "unique:users"],
                "project_id" => "required",
                "father_name" => "sometimes|nullable|max:150",
                "birth_date" => "sometimes|nullable|max:100|jdate:Y/m/d",
                "national_code" => ["required", "digits:10", new NationalCodeChecker, "unique:users"],
                "identify_number" => "sometimes|nullable|numeric",
                "address" => "sometimes|nullable|max:500"
            ];
        }
        elseif ($this->method() == "PUT"){
            return [
                'name' => 'required|string|max:255',
                'username' => ['required','string','max:255','unique:users,username,'.$this->route('User').",id"],
                'password' => 'sometimes|nullable|string|min:8|confirmed',
                'role_id' => 'required',
                'email' => ['sometimes','nullable','string','email','max:255','unique:users,email,'.$this->route('User').",id"],
                "mobile" => ["required", "regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/", "unique:users,mobile,".$this->route('User').",id"],
                "project_id" => "required",
                "father_name" => "sometimes|nullable|max:150",
                "birth_date" => "sometimes|nullable|max:100|jdate:Y/m/d",
                "national_code" => ["required", "digits:10", new NationalCodeChecker, "unique:contractors,national_code,".$this->route('Contractor').",id"],
                "identify_number" => "sometimes|nullable|numeric",
                "address" => "sometimes|nullable|max:500"
            ];
        }
        else return [];
    }
    public function messages(): array
    {
        return [
            "name.required" => "درج نام کاربر الزامی می باشد.",
            "name.max" => "طول نام حداکثر 255 کاراکتر می باشد.",
            "username.required" => "درج نام کاربری الزامی می باشد.",
            "username.string" => "نام کاربری باید به صورت رشته ای از حروف و اعداد باشد.",
            "username.max" => "طول نام کاربری حداکثر 255 کاراکتر می باشد.",
            "username.unique" => "نام کاربری درج شده تکراری می باشد.",
            "password.required" => "درج کلمه عبور الزامی می باشد.",
            "password.string" => "کلمه عبور باید ترکیبی از اعداد و حروف باشد.",
            "password.min" => "طول کلمه عبور حداقل باید 8 کاراکتر باشد.",
            "password.confirmed" => "کلمه عبور و تکرار آن با هم همخوانی ندارند.",
            "role_id.required" => "انتخاب سمت الزامی می باشد.",
            "email.string" => "پست الکترونیکی باید تلفیقی از اعداد و حروف باشد.",
            "email.email" => "پست الکترونیکی درج شده در فرمت صحیح نمی باشد.",
            "email.max" => "طول پست الکترونیکی حداکثر 255 کاراکتر می باشد.",
            "email.unique" => "پست الکترونیکی درج شده تکراری می باشد.",
            "mobile.required" => "درج تلفن همراه الزامی میباشد.",
            "mobile.regex" => "تلفن همراه درج شده در فرمت صحیح نمی باشد.",
            "mobile.unique" => "تلفن همراه درج شده تکراری می باشد.",
            "project_id.required" => "انتخاب پروژه های مجاز به دسترسی الزامی می باشد.",
            "father_name.max" => "طول نام پدر حداکثر به اندازه 500 کاراکتر قابل قبول است.",
            "birth_date.max" => "طول تاریخ تولد ثبت شده حداکثر 100 کاراکتر قابل قبول است.",
            "birth_date.jdate" => "تاریخ تولد درج شده در فرمت صحیح نمی باشد.",
            "national_code.required" => "درج کد ملی پیمانکار الزامی می باشد.",
            "national_code.unique" => "کد ملی درج شده تکراری می باشد.",
            "national_code.digits" => "کد ملی پیمانکار 10 کاراکتر می باشد.",
            "identify_number.numeric" => "شماره شناسنامه مقدار عددی نمی باشد.",
            "address.max" => "طول آدرس حداکثر 500 کاراکتر قابل قبول است."
        ];
    }
}
