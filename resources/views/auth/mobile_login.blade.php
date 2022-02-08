<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        {{$company_information->name}}
        -
        صفحه ورود
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="{{asset("/css/app.css?v=".time())}}" rel="stylesheet">
    <link href="{{asset("/css/mobile_login.css?v=".time())}}" rel="stylesheet">
    @laravelPWA
</head>
<body class="antialiased rtl bg-dark">
<div id="app">
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-circle-notch fa-spin white_color fa-2x"></i>
    </div>
    <div class="container-fluid">
        <div class="logo-container">
            <img class="logo mb-2" src="{{asset("/img/mobile_logo.png")}}" alt="منتظر بمانید...">
            <span class="company_name">{{$company_information->name}}</span>
        </div>
        <form class="w-100 d-flex flex-column justify-content-center align-items-center" id="login_form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="login_inputs">
                <div class="position-relative w-100 text-center">
                    <input type="text" id="username" name="username" class="form-control login_input text-center iran_yekan @error('username') is-invalid @enderror">
                    <span id="username_label" class="iran_yekan input_label">نام کاربری</span>
                </div>
                <hr/>
                <div class="position-relative w-100 text-center">
                    <input type="password" id="password" name="password" class="form-control login_input text-center iran_yekan @error('username') is-invalid @enderror">
                    <span id="password_label" class="iran_yekan input_label">گذرواژه</span>
                </div>
                <div class="form-row position-relative mt-2">
                    <input type="hidden" class="login_input iran_yekan form-control @error('username') is-invalid @enderror">
                    @error('username')
                    <span class="invalid-feedback iran_yekan w-100 text-center" role="alert" style="font-size: 10px">
                                        {{ $message }}
                            </span>
                    @enderror
                </div>
                <hr/>
                <div class="position-relative w-100 text-center">
                    <div class="g-recaptcha m-auto d-inline-block" data-sitekey="{{env('GOOGLE_RECAPTCHA_SITE_KEY')}}"></div>
                </div>
                <div class="col-12 text-center">
                    <div class="@error('g-recaptcha-response') is-invalid @enderror"></div>
                    @error('g-recaptcha-response')
                    <span class="invalid-feedback iran_yekan d-block text-center" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <hr/>
                <button type="submit" class="login_button iran_yekan mb-5">
                    <i class="fas fa-sign-in-alt" style="font-size: 1.2rem;vertical-align: middle"></i>
                    ورود به داشبورد
                </button>
                <a class="iran_yekan forget_button">گذرواژه خود را فراموش کرده ام</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{{asset("/js/app.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/js/mobile_login.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/serviceworker.js?v=".time())}}"></script>
<script src="{{asset("/serviceworker.js?v=".time())}}"></script>
<script async src="https://www.google.com/recaptcha/api.js?hl=fa"></script>
</body>
</html>
