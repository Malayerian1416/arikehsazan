<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>
        بازنشانی گذرواژه
    </title>
    <link href="{{asset("/css/app.css?v=".time())}}" rel="stylesheet">
    @yield('styles')
</head>
<body class="antialiased rtl" style="background: #F8F8F8">
<div class="container pt-5 w-50">
    @if($errors->any())
        <div class="iran_yekan alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 13px">
            <i class="fa fa-times-circle" style="color: #ff0000;min-width: 30px;vertical-align: middle;text-align:center;font-size: 1.5rem"></i>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row border bg-white no-gutters">
        <div class="col-12 bg-primary p-2 d-flex align-items-center">
            <span class="iran_yekan white_color mb-0" style="font-size: 14px">رمز عبور خود را فراموش کرده اید؟ برای شروع روند بازیابی آن، لطفا آدرس ایمیل خود را در زیر وارد کنید.</span>
        </div>
        <div class="col-12 p-3">
            <form id="reset_form" method="post" action="{{route("Password.verify")}}">
                @csrf
                <input type="text" class="form-control text-center" placeholder="Email" autofocus name="email">
            </form>
        </div>
        <div class="col-12 p-3 text-center">
            <button type="submit" form="reset_form" class="btn btn-outline-primary">
                <span class="iran_yekan">ادامه</span>
                <i class="fa fa-arrow-circle-left"></i>
            </button>
        </div>
    </div>
</div>
<script src="{{asset("js/app.js?v=".time())}}"></script>
</body>
</html>
