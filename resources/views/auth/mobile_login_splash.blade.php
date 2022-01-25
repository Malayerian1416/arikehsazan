<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
    </title>
    <!-- Styles -->
    <link rel="manifest" href="{{asset("/mobile.webmanifest")}}">
    <link href="{{asset("/css/app.css")}}" rel="stylesheet">
    <link href="{{asset("/css/mobile_login.css")}}" rel="stylesheet">
</head>
<body class="antialiased">
<div class="container-fluid login_bg rtl">
    <img src="{{asset("/img/logo.png")}}">
    <h3 class="company_title iran_nastaliq">{{$company_information->name}}</h3>
    <h6 class="company_desc iran_yekan mt-1 pt-1 white_color">{{$company_information->description}}</h6>
    <h6 class="company_desc iran_yekan white_color" style="padding-top: 15px">سامانه جامع مدیریت شرکت های عمرانی</h6>
</div>
<script type="text/javascript" src="{{asset("/js/app.js")}}"></script>
<script>
    $(document).ready(function (){
        let redirect_Page = () => {
            let tID = setTimeout(function () {
                window.location.href = "{{route("login")}}";
                window.clearTimeout(tID);		// clear time out.
            }, 2000);
        }
        redirect_Page();
    });
</script>
</body>
</html>
