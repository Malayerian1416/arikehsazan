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
    <link href="{{asset("/css/app.css")}}" rel="stylesheet">
    <link href="{{asset("/css/animate.min.css")}}" rel="stylesheet">
    <link href="{{asset("/css/mobile_login.css")}}" rel="stylesheet">
    @laravelPWA
</head>
<body class="antialiased rtl">
<div id="app">
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-circle-notch fa-spin white_color fa-2x"></i>
    </div>
    <div class="container-fluid">
        <div class="logo-container">
            <img class="logo mb-2" src="{{asset($company_information->logo)}}" alt="منتظر بمانید...">
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
                    <input type="password" id="password" name="password" class="form-control login_input text-center iran_yekan @error('password') is-invalid @enderror">
                    <span id="password_label" class="iran_yekan input_label">گذرواژه</span>
                </div>
                @error('username')
                <span class="invalid-feedback iran_yekan w-100 text-center" role="alert" style="font-size: 10px">
                                        {{ $message }}
                            </span>
                @enderror
                <hr/>
                <button type="submit" class="login_button iran_yekan mb-5">ورود به داشبورد</button>
                <button type="button" class="forget_button iran_yekan">فراموشی اطلاعات ورود</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{{asset("/js/app.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/mobile_login.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/serviceworker.js")}}"></script>
{{--<script src="https://www.google.com/recaptcha/api.js?hl=fa" async defer></script>--}}
</body>
</html>
