<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function passes($attribute, $value): bool
    {
        $client = new Client();
        $response = $client->request("POST","https://www.google.com/recaptcha/api/siteverify",[
            "form_params" => [
                "secret" => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
                "response" => $value,
                "remoteip" => request()->ip()
            ]
        ]);
        $response = json_decode($response->getBody());
        return $response->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'شما به عنوان ربات تشخیص داده شده اید.';
    }
}
