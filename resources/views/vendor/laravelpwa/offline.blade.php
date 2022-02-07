<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{asset("/css/app.css?v=".time())}}" rel="stylesheet">
    <title></title>
    <style>
        *{
            font-size: 11px;
        }
        body{
            width: 100vw;height: 100vh;overflow: hidden
        }
        .container{
            width: 100%;height: 100%
        }
        .disconnect_text{
            color: #ffc64b
        }
        .retry_button{
            font-size: 9px;
        }
    </style>
</head>
<body class="bg-dark">
<div class="container d-flex justify-content-center align-items-center flex-column">
    <img class="disconnect_img" src="{{asset("/img/unplug-icon.png")}}" alt="">
    <span class="iran_yekan disconnect_text mt-2">عدم اتصال اینترنت</span>
    <button class="btn btn-outline-warning iran_yekan mt-5 retry_button" onclick="location.href = '{{route("login")}}'">
        تلاش مجدد
    </button>
</div>
</body>
</html>
