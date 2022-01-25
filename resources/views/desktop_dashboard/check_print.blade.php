<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>
    </title>
    <!-- Styles -->
    <link href="{{asset("/css/app.css")}}" rel="stylesheet">
    <link href="{{asset("/css/check_sample.css")}}" rel="stylesheet">
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
</head>
<body class="antialiased rtl">
<div class="container border mt-3">
    <div class="col-12">
        <div class="d-flex flex-row align-items-center justify-content-around">
            <div class="form-group col-3">
                <label class="col-form-label">مبلغ</label>
                <input class="form-control" type="text">
            </div>
        </div>
    </div>
    <div class="col-12 text-center">
        <div class="check-sample_container">
            <img id="check_sample" src="{{asset("/img/check_sample.jpg")}}" class="img-fluid">
            <div class="date_number_day">05</div>
            <div class="date_number_month">12</div>
            <div class="date_number_year">1400</div>
            <div class="date_character">پنجم اسفند ماه یک هزار و چهارصد</div>
            <div class="amount_character">دویست و پنجاه سه میلیون هشتصد و پنجاه هزار ریال/ـــــــ</div>
            <div class="receiver_name">مسعود ملایریان حسنی</div>
            <div class="receiver_national_code">0945410581</div>
            <div class="amount_number">
                <div class="item1">0</div>
                <div class="item2">5</div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset("/js/app.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
<script type="text/javascript" src="{{asset("/js/numeral.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}"></script>
<script type="module" src="{{asset("/js/kernel.js")}}" defer></script>
</body>
</html>
