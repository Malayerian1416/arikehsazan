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
<body class="antialiased rtl iran_yekan" style="background: #F8F8F8">
<div class="container" style="width: 100vw;height: 100vh">
    <div class="row h-100 justify-content-center">
        <div class="col-md-8 d-flex justify-content-center align-items-center h-100">
            <div class="card" style="width: 90%">
                <div class="card-header iran_yekan">تغییر گذرواژه</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <input id="email" type="email" class="form-control text-center @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ایمیل خود را وارد کنید" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mt-2 text-center">
                                <button type="submit" class="btn btn-primary iran_yekan">
                                    دریافت لینک تغییر گذرواژه
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
