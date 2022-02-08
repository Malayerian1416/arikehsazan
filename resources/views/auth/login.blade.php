<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{$company_information->name}}
        -
        صفحه ورود
    </title>
    <!-- Styles -->
    <link href="{{asset("/css/app.css?v=".time())}}" rel="stylesheet">
    <link href="{{asset("/css/login.css?v=".time())}}" rel="stylesheet">
    @laravelPWA
</head>
<body class="antialiased">
<div class="rtl container-fluid login_bg">
    <div class="login_window">
        <div class="w-100 login_body">
            <div class="side">
                <div class="text-center iran_nastaliq header">
                    <h3 class="company_title nastaliq">{{$company_information->name}}</h3>
                    <h6 class="company_desc iran_yekan mt-1 pt-1 white_color">{{$company_information->description}}</h6>
                    <h6 class="company_desc iran_yekan white_color" style="padding-top: 15px">سامانه جامع مدیریت شرکت های عمرانی</h6>
                </div>
                <div class="text-center footer">
                    <img class="img_logo" src="{{asset($company_information->logo)}}">
                </div>
            </div>
            <div class="content text-center">
                <h4 class="iran_yekan pb-4 black_color">صفحه ورود به ســیسـتم</h4>
                <div>
                    <form id="login_form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-row position-relative mb-3">
                            <input type="text" class="login_input iran_yekan form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="نام کاربری">
                            <i class="fa fa-user fa-2x login_input_icon"></i>
                        </div>
                        <div class="form-row position-relative mb-3">
                            <input type="password" class="login_input iran_yekan form-control @error('username') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}"  placeholder="گذرواژه">
                            <i class="fa fa-lock fa-2x login_input_icon"></i>
                        </div>
                        <div class="form-row position-relative mb-3">
                            <input type="hidden" class="login_input iran_yekan form-control @error('username') is-invalid @enderror">
                            @error('username')
                            <span class="invalid-feedback iran_yekan w-100 text-center" role="alert" style="font-size: 10px">
                                        {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="col-12 text-center">
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
                        </div>
                        <div class="form-row mt-1">
                            <div class="form-group col-12 text-left">
                                <input type="checkbox" style="vertical-align: middle" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label iran_yekan p-0 m-0" style="vertical-align: middle" for="remember">مرا به خاطر بسپار</label>
                            </div>
                        </div>
                        <div class="form-row pb-3">
                            <div class="form-group col-12 text-left">
                                <a href="" class="iran_yekan black_font">گذرواژه خود را فراموش کرده اید؟</a>
                            </div>
                        </div>
                    </form>
                </div>
                <button id="submit" type="submit" class="btn btn-outline-dark iran_yekan w-100 login_button">
                    <i class="button_loading fa fa-spinner fa-spin mr-2"></i>
                    <span class="button_text">ورود به داشـبورد</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset("/js/app.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/js/login.js?v=".time())}}"></script>
<script src="{{asset("/serviceworker.js?v=".time())}}"></script>
<script async src="https://www.google.com/recaptcha/api.js?hl=fa"></script>
</body>
</html>
