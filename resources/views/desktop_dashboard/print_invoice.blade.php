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
                size: A4 landscape;
            }
        }
    </style>
</head>
<body class="antialiased rtl">
<h3 class="d-flex flex-row justify-content-between align-items-center">
    <span style="font-size: 60%">چاپ وضعیت</span>
    <span style="font-size: 100%">شرکت اریکه سازان توس</span>
    <span style="font-size: 60%">{{verta()->format("Y/m/d")}}</span>
</h3>
<table class="table table-bordered w-100 contract_information">
    <thead>
    <tr>
        <th>پروژه</th>
        <th>پیمان</th>
        <th>رشته</th>
        <th>سرفصل</th>
        <th>پیمانکار</th>
        <th>شماره</th>
        <th>قطعی</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$invoice->contract->project->name}}</td>
        <td>{{$invoice->contract->name}}</td>
        <td>{{$invoice->contract->category->branch->branch}}</td>
        <td>{{$invoice->contract->category->category}}</td>
        <td>{{$invoice->contract->contractor->name}}</td>
        <td>{{$invoice->number}}</td>
        <td>@if($invoice->is_final)بلی@elseخیر@endif</td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered text-center">
    <thead>
    <tr>
        <th colspan="{{count($invoice->automation_amounts)}}" scope="col" class="border border-muted">کـــــارکــــرد</th>
        <th scope="col" class="bg-white border-0" style="border-top: none"></th>
        <th colspan="{{count($invoice->automation_amounts)}}" class="border border-muted" scope="col">بـــهــاء جــزء(ریال)</th>
        <th colspan="{{count($invoice->automation_amounts)}}" class="border border-muted" scope="col">بـــهــاء کــل(ریال)</th>
    </tr>
    <tr>
        @forelse($invoice->automation_amounts as $automation_amount)
            <th scope="col">{{$automation_amount->user->role->name}}</th>
        @empty
        @endforelse
        <th scope="col">واحد</th>
        @forelse($invoice->automation_amounts as $automation_amount)
            <th scope="col">{{$automation_amount->user->role->name}}</th>
        @empty
        @endforelse
        @forelse($invoice->automation_amounts as $automation_amount)
            <th scope="col">{{$automation_amount->user->role->name}}</th>
        @empty
        @endforelse
    </tr>
    </thead>
    <tbody>
    <tr>
        @forelse($invoice->automation_amounts as $quantity)
            <td>{{$quantity->quantity}}</td>
        @empty
        @endforelse
        <td>{{$invoice->contract->unit->name}}</td>
        @forelse($invoice->automation_amounts as $amount)
            <td>{{number_format($amount->amount)}}</td>
        @empty
        @endforelse
        @forelse($invoice->automation_amounts as $total_amount)
            <td>{{number_format($total_amount->quantity * $total_amount->amount)}}</td>
        @empty
        @endforelse
    </tr>
    </tbody>
</table>
<div class="d-flex flex-row justify-content-between">
    <div class="w-50">
        @if($invoice->extras->isNotEmpty())
            <table id="extra_work_table" class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col" colspan="2" style="padding: 0.3rem">اضافه کار ثبت شده</th>
                </tr>
                <tr>
                    <th>شرح</th>
                    <th>مبلغ(ریال)</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoice->extras as $extra)
                    <tr>
                        <td style="width: 65%">{{$extra->description}}</td>
                        <td style="width: 35%">{{number_format($extra->amount)}}</td>
                    </tr>
                @empty
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th>جمع کل</th>
                    <th>{{number_format(array_sum($invoice->extras->pluck("amount")->toArray()))}}</th>
                </tr>
                </tfoot>
            </table>
        @endif
        @if($invoice->deductions->isNotEmpty())
            <table id="deduction_work_table" class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col" colspan="2" style="padding: 0.3rem">کسر کار ثبت شده</th>
                </tr>
                <tr>
                    <th>شرح</th>
                    <th>مبلغ(ریال)</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoice->deductions as $deduction)
                    <tr>
                        <td style="width: 65%">{{$deduction->description}}</td>
                        <td style="width: 35%">{{number_format($deduction->amount)}}</td>
                    </tr>
                @empty
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th>جمع کل</th>
                    <th>{{number_format(array_sum($invoice->deductions->pluck("amount")->toArray()))}}</th>
                </tr>
                </tfoot>
            </table>
        @endif
    </div>
    <table class="table table-bordered w-50">
        <thead>
        <tr>
            <th scope="col" colspan="3" style="padding: 0.3rem">مجموع قابل پرداخت</th>
        </tr>
        <tr>
            <th>سمت</th>
            <th>جمع کل(ریال)</th>
            <th>پیشنهاد پرداخت(ریال)</th>
        </tr>
        </thead>
        <tbody>
        @forelse($invoice->automation_amounts as $total_payable)
            <tr>
                <td>{{$total_payable->user->role->name}}</td>
                <td>
                    {{number_format(($total_payable->amount * $total_payable->quantity) + array_sum($invoice->extras->pluck("amount")->toArray()) - array_sum($invoice->deductions->pluck("amount")->toArray()))}}
                </td>
                <td>
                    <div class="d-flex flex-row justify-content-around">
                        <span class="text-center">{{$total_payable->payment_offer_percent."%"}}</span>
                        <span class="text-center">{{number_format($total_payable->payment_offer)}}</span>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <th class="border">جمع کل</th>
            <th colspan="2" class="border">
                @if($main_amounts)
                    {{number_format(($main_amounts->quantity * $main_amounts->amount) + array_sum($invoice->extras->pluck("amount")->toArray()) - array_sum($invoice->deductions->pluck("amount")->toArray()))}}
                @else
                    {{"ثبت نشده"}}
                @endif
            </th>
        </tr>
        <tr>
            <th class="border">قابل پرداخت</th>
            <th colspan="2" class="border">
                @if($main_amounts)
                    {{number_format($main_amounts->payment_offer)}}
                @else
                    {{"ثبت نشده"}}
                @endif
            </th>
        </tr>
        </tfoot>
    </table>
</div>
@if($invoice->signs->isNotEmpty())
    <div class="w-100">
        <label class="col-form-label iran_yekan black_color" for="project_name">امضاء شده توسط</label>
        <div class="sign_container">
            @forelse($invoice->signs as $sign)
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
