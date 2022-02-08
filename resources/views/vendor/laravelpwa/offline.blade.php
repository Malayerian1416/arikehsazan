<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{asset("css/app.css")}}" rel="stylesheet">
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
            color: #ffc64b;
            font-size: 13px;
        }
        .retry_button{
            font-size: 10px;
        }
    </style>
</head>
<body class="bg-dark rtl">
<div class="container d-flex justify-content-center align-items-center flex-column">
    <img class="disconnect_img" src="{{asset("/img/unplug-icon.png")}}" alt="">
    <span class="iran_yekan disconnect_text mt-3">مشکل در اتصال اینترنت</span>
    <button class="btn btn-outline-warning iran_yekan mt-5 retry_button" onclick="location.href = '{{route("login")}}'">
        تلاش مجدد
    </button>
</div>
<script>
    window.addEventListener("load", () => {
        const checker = setTimeout(check_connection,3000);
        function check_connection(){
            if(navigator.onLine){
                clearTimeout(checker);
                location.href = '{{route("login")}}';
            }
            return false
        }
    });
</script>
</body>
</html>
