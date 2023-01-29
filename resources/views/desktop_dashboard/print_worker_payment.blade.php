<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="">
    <title>
    </title>
    <!-- Styles -->
    <link href="{{asset("/css/app.css")}}" rel="stylesheet">
    <style>
        *{
            font-size: 13px;!important;
            font-family: mitra,'sans-serif';
        }
        .table th,td{
            text-align: center;
        }
        .sign_container{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-start;
        }
        .sign_box{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 10px;
            border: 2px dotted #dcdcdc;
            min-width: 90px;
        }
        @media print {
            @page  {
                size: A4 portrait;
            }
        }
    </style>
</head>
<body class="antialiased rtl">
<h3 class="d-flex flex-row justify-content-between align-items-center">
    <span style="font-size: 60%">رسید پرداختی کارگری</span>
    <span style="font-size: 100%">شرکت اریکه سازان توس</span>
    <span style="font-size: 60%">{{verta()->format("Y/m/d")}}</span>
</h3>
<table class="table table-bordered w-100 contract_information">
    <thead>
    <tr>
        <th>پروژه</th>
        <th>نام کارگر</th>
        <th>تاریخ ایجاد</th>
        <th>تاریخ پرداخت</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$worker_automation->project->name}}</td>
        <td>{{$worker_automation->contractor->name}}</td>
        <td>{{verta($worker_automation->created_at)->format("Y/n/d")}}</td>
        <td>{{verta($worker_automation->updated_at)->format("Y/n/d")}}</td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered w-100 contract_information">
    <thead>
    <tr>
        <th>بابت</th>
        <th>مبلغ</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$worker_automation->description}}</td>
        <td style="font-weight: 800">{{number_format($worker_automation->amount)}}</td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered w-100 contract_information">
    <thead>
    <tr>
        <th>نوع پرداخت</th>
        <th>شماره حساب</th>
        <th>شماره رسید پرداخت</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$worker_automation->payments->deposit_kind_string}}</td>
        <td>{{$worker_automation->payments->deposit_kind_number}}</td>
        <td>{{$worker_automation->payments->payment_receipt_number}}</td>
    </tr>
    </tbody>
</table>
@if($worker_automation->signs->isNotEmpty())
    <div class="w-100">
        <label class="col-form-label iran_yekan black_color" for="project_name">امضاء شده توسط</label>
        <div class="sign_container">
            @forelse($worker_automation->signs as $sign)
                <div class="sign_box iran_yekan bg-light mr-4">
                    <span class="text-muted">{{$sign->user->role->name}}</span>
                    <span>{{$sign->user->name}}</span>
                    <span class="text-muted" dir="ltr" style="font-size: 10px">{{verta($sign->created_at)->format("Y/m/d")}}</span>
                </div>
            @empty
            @endforelse
        </div>
    </div>
@endif
<script type="text/javascript" src="{{asset("/js/app.js")}}"></script>
<script>
    $(document).ready(function (){
        window.onafterprint = window.close;
        window.print();
    });
</script>
</body>
</html>
