<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractBranchRequest extends FormRequest
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
            "branch" => "required|max:500"
        ];
    }
    public function messages()
    {
        return [
          "branch.required" => "درج نام شاخه الزامی می باشد.",
          "branch.max" => "حداکثر طول نام شاخه 500 کاراکتر می باشد."
        ];
    }
}
