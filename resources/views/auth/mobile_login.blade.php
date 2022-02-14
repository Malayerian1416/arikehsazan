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
<body class="antialiased rtl">
<div id="app">
    <div class="loading_window">
        <i class="fas fa-circle-notch fa-spin white_color fa-2x"></i>
    </div>
    <div class="container-fluid">
        <div class="logo-container">
            <img class="logo mb-2" src="{{asset("/img/mainlogo.png")}}" alt="منتظر بمانید...">
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
                <div class="position-relative w-100 text-center mt-1">
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
                <button type="submit" class="btn btn-outline-info form-control iran_yekan mb-5 login_button" onclick="$('.loading_window').css('display','flex')">
                    <i class="fas fa-sign-in-alt login_button_icon" ></i>
                    ورود به داشبورد
                </button>
                <a class="iran_yekan forget_button" href="{{route("password.request")}}">گذرواژه خود را فراموش کرده ام</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="{{asset("/js/app.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/js/mobile_login.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/serviceworker.js?v=".time())}}"></script>
</body>
</html>
