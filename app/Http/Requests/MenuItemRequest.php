<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
            "menu_header_id" => "required",
            "parent_id" => "sometimes|nullable",
            "menu_action_id" => "sometimes|nullable",
            "short_name" => "required|max:255",
            "route" => "sometimes|nullable",
            "main" => "sometimes|nullable",
            "notifiable" => "sometimes|nullable",
            "notification_channel" => "sometimes|nullable",
            "priority" => "sometimes|nullable"
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "درج نام الزامی می باشد.",
            "name.max" => "حداکثر طول نام 500 کاراگتر می باشد.",
            "short_name.required" => "درج نام مختصر الزامی می باشد.",
            "short_name.max" => "حداکثر طول نام مختصر 500 کاراگتر می باشد.",
            "menu_title_id.required" => "انتخاب منوی اصلی الزامی می باشد.",
            "menu_action_id.required" => "انتخاب حداقل یک عنوان از عملیات وابسته الزامی می باشد.",
            "main.required" => "انتخاب عنوان اصلی الزامی می باشد.",
        ];
    }
}
