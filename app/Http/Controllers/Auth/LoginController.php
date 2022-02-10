<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyInformation;
use App\Providers\RouteServiceProvider;
use App\Rules\recaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        $company_information = CompanyInformation::all()->first();
        $agent = new Agent();
        if ($agent->isDesktop())
            return view('auth.login',["company_information" => $company_information]);
        else if($agent->isPhone() || $agent->isTablet())
            return view('auth.mobile_login',["company_information" => $company_information]);
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
    }
    public function username(): string
    {
        return 'username';
    }
    public function password(): string
    {
        return 'password';
    }
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), $this->password());
    }
    protected function validateLogin(Request $request)
    {
        $agent = new Agent();
        if ($agent->isDesktop()) {
            $request->validate([
                $this->username() => 'required|string',
                $this->password() => 'required|string',
                "g-recaptcha-response" => ['required', new recaptcha()]
            ], ['g-recaptcha-response.required' => 'لطفا روی گزینه من ربات نیستم کلیک کنید']);
        }
        else if($agent->isPhone() || $agent->isTablet()) {
            $request->validate([
                $this->username() => 'required|string',
                $this->password() => 'required|string']);
        }
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['اطلاعات وارد شده در سامانه موجود نمی باشد'],
        ]);
    }
    protected function authenticated(Request $request, $user)
    {
        return redirect("/Dashboard");
    }
    public function redirectPath(): string
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/Dashboard';
    }
}
