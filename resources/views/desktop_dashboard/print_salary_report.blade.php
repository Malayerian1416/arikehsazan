<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="">
    <title>
    </title>
    <!-- Styles -->
    <link href="{{asset("/css/app.css")}}" rel="stylesheet">
    <style>
        *{
            font-size: 15px;!important;
            font-family: mitra,'sans-serif';
        }
        table{
            page-break-after: auto;
        }
        table th, table td{
            text-align: center!important;
            border: 1px solid #c0c0c0;
            padding: 2px 5px;
        }
        table th{
            padding: 10px;
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
    <span class="text-left" style="font-size: 70%;width: 33%">{{"کارکرد پرسنل ".$staff_name." ($date_range) "}}</span>
    <span class="text-center" style="font-size: 100%;width: 33%">شرکت اریکه سازان توس</span>
    <span class="text-right" style="font-size: 70%;width: 33%">{{verta()->format("Y/m/d")}}</span>
</h3>
<table class="w-100">
    <thead>
    <tr>
        <th scope="col">تاریخ</th>
        <th scope="col">روز هفته</th>
        <th scope="col">حضور/غیاب</th>
        <th scope="col">محل کار</th>
        <th scope="col">مجموع</th>
        <th scope="col">کارکرد</th>
        <th scope="col">مرخصی</th>
        <th scope="col">تاخیر</th>
        <th scope="col">تعجیل</th>
        <th scope="col">کسر کار</th>
        <th scope="col">اضافه کار</th>
        <th scope="col">اضافه آزاد</th>
        <th scope="col">وضعیت</th>
    </tr>
    </thead>
    @if(isset($results))
        <tbody>
        @forelse($results as $result)
            @if($result["status"] == 0)
                <tr>
                    <td><span>{{$result["date"]}}</span></td>
                    <td><span>{{$result["day"]}}</span></td>
                    @if(count($result["attendances"]) > 0)
                        <td>
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                @forelse(array_chunk($result["attendances"], 2) as $group)
                                    <div class="w-100 d-flex flex-row align-items-center justify-content-between">
                                        @foreach($group as $item)
                                            <div class="w-100">
                                                @if($item["type"] == "presence")
                                                    <span>{{"ورود : ".$item["time"]}}</span>
                                                @elseif($item["type"] == "absence")
                                                    <span>{{"خروج : ".$item["time"]}}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </td>
                    @else
                        <td><span>ندارد</span></td>
                    @endif
                    <td><span>{{$result["location"]}}</span></td>
                    <td><span>{{$result["total_work_duration"]}}</span></td>
                    <td><span>{{$result["operation"]}}</span></td>
                    <td><span>{{$result["total_hourly_leave_duration"]}}</span></td>
                    <td>
                        <div class="d-flex flex-column justify-content-center align-items-center w-100">
                            <span>{{$result["delay"]}}</span>
                            <span>{{number_format($result["delay_amount"])}}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column justify-content-center align-items-center w-100">
                            <span>{{$result["acceleration"]}}</span>
                            <span>{{number_format($result["acceleration_amount"])}}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column justify-content-center align-items-center w-100">
                            <span>{{$result["total_absence_duration"]}}</span>
                            <span>{{number_format($result["absence_amount"])}}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column justify-content-center align-items-center w-100">
                            <span>{{$result["total_overtime_work_duration"]}}</span>
                            <span>{{number_format($result["overtime_work_amount"])}}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column justify-content-center align-items-center w-100">
                            <span>{{$result["total_free_overtime_work_duration"]}}</span>
                            <span>{{number_format($result["free_overtime_work_amount"])}}</span>
                        </div>
                    </td>
                    <td><span>{{$result["attendance"]}}</span></td>
                </tr>
            @elseif($result["status"] == 1)
                <tr>
                    <td><span>{{$result["date"]}}</span></td>
                    <td><span>{{$result["day"]}}</span></td>
                    <td colspan="10">{{$result["err_message"]}}</td>
                    <td><span>{{$result["attendance"]}}</span></td>
                </tr>
            @endif
        @empty
        @endforelse
        </tbody>
    @endif
</table>
</div>
@if(isset($totals))
    <div class="mb-3 mt-3 total_amounts_window">
        <table class="table table-bordered iran_yekan">
            <thead>
            <tr>
                <th class="text-center" colspan="12">جمع مقادیر</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><span>کل روزها</span></td>
                <td><span>روزهای حضور</span></td>
                <td><span>روزهای تعطیل</span></td>
                <td><span>روزهای مرخصی</span></td>
                <td><span>روزهای غیبت</span></td>
                <td><span>غیبت(غیرمجاز)</span></td>
                <td><span>دستمزد دوره</span></td>
                <td><span>جمع تاخیر</span></td>
                <td><span>جمع تعجیل</span></td>
                <td><span>جمع کسر کار</span></td>
                <td><span>جمع اضافه کار</span></td>
                <td><span>خالص پرداختی</span></td>
            </tr>
            <tr>
                <td><span>{{$totals["total_days"]}}</span></td>
                <td><span>{{$totals["total_Presence_day"]}}</span></td>
                <td><span>{{$totals["total_holidays"]}}</span></td>
                <td><span>{{$totals["total_leaves"]}}</span></td>
                <td><span>{{$totals["total_absence_day"]}}</span></td>
                <td><span>{{$totals["total_absence_day_illegal"]}}</span></td>
                <td><span>{{$totals["total_wage"]}}</span></td>
                <td><span>{{$totals["total_delay"]}}</span></td>
                <td><span>{{$totals["total_acceleration"]}}</span></td>
                <td><span>{{$totals["total_absence"]}}</span></td>
                <td><span>{{$totals["total_overtime_work"]}}</span></td>
                <td><span>{{$totals["total_payable"]}}</span></td>
            </tr>
            </tbody>
        </table>
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
