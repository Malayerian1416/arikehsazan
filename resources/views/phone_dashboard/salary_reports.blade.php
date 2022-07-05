@extends('phone_dashboard.p_dashboard')
@section('scripts')
    <script src="{{asset("/js/jalali-moment.browser.js")}}"></script>
    <script>
        $(document).ready(function (){
            moment.locale('fa');
            $("#from_date").persianDatepicker({
                onSelect:function (){
                    $("#to_date").val('');
                    $("#holidays").html('').prop("disabled",true).selectpicker('refresh');
                    $("#holiday_sticker").addClass("fa-times-circle").addClass("red_color").removeClass("fa-check-circle").removeClass("green_color");
                }
            });
            $("#to_date").persianDatepicker({
                onSelect: function () {
                    const first_date = $("#from_date").attr("data-gdate");
                    const last_date = $("#to_date").attr("data-gdate");
                    var d1 = new Date(first_date);
                    var d2 = new Date(last_date);
                    if(typeof first_date !== 'undefined'){
                        if(d2.getTime() > d1.getTime()) {
                            $("#holidays").html('').prop("disabled", false);
                            $("#holiday_sticker").removeClass("fa-times-circle").removeClass("red_color").addClass("fa-check-circle").addClass("green_color");
                            console.log()
                            const m_last_date = moment.from(last_date, 'en', 'YYYY/MM/DD').add(1, 'd').format('YYYY/MM/DD');
                            let i = 1;
                            let date = moment.from(first_date, 'en', 'YYYY/MM/DD').format('YYYY/MM/DD');
                            while (date !== m_last_date) {
                                $("#holidays").append(`<option value='${date}'>${date}</option>`).selectpicker('refresh');
                                date = moment.from(first_date, 'en', 'YYYY/MM/DD').add(i, 'd').format('YYYY/MM/DD');
                                i++;
                            }
                        }
                        else {
                            bootbox.alert({
                                message: "تاریخ انتها قبل از تاریخ ابتدا انتخاب شده است",
                                closeButton: false, centerVertical: true,
                                buttons: {
                                    ok: {
                                        label: 'قبول',
                                        className: 'btn-primary',
                                    }
                                },
                            });
                            $("#to_date").val('');
                            $("#holidays").html('').prop("disabled",true).selectpicker('refresh');
                            $("#holiday_sticker").addClass("fa-times-circle").addClass("red_color").removeClass("fa-check-circle").removeClass("green_color");
                        }
                    }
                }
            });
        });
    </script>
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">گزارشات حقوق و دستمزد</span>
@endsection
@section('content')
    <form action="{{route("Reports.salary_reports_make")}}" method="post">
        @csrf
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">مشخصات گزارش</h6>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="staff_id">پرسنل</label>
                <select class="form-control select_picker iran_yekan @error('staff_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="staff_id" name="staff_id" v-on:change="set_work_shift">
                    @forelse($staffs as $staff)
                        <option @if(isset($old_data["staff_id"]) && $old_data["staff_id"] == $staff->id) selected @endif data-work_shift="{{$staff->work_shift->id}}" @if($staff->id == old("staff_id")) selected @elseif(isset($staff_id) && $staff->id == $staff_id) selected @endif value="{{$staff->id}}">{{$staff->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('staff_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color">از تاریخ</label>
                <input type="text" readonly class="form-control @error('from_date') is-invalid @enderror iran_yekan text-center" id="from_date" name="from_date" autocomplete="off" value="@if(isset($old_data["from_date"])) {{$old_data["from_date"]}} @else {{old("from_date")}} @endif">
                @error('from_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message." (مثلا : 1400/02/01)" }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="project_name">تا تاریخ</label>
                <input type="text" readonly class="form-control @error('to_date') is-invalid @enderror iran_yekan text-center" id="to_date" name="to_date" autocomplete="off" value="@if(isset($old_data["to_date"])) {{$old_data["to_date"]}} @else {{old("to_date")}} @endif">
                @error('to_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message." (مثلا : 1400/02/01)" }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="holidays">
                    <i id="holiday_sticker" class="fa fa-times-circle fa-1_4x red_color" style="vertical-align: middle"></i>
                    ایام تعطیل رسمی
                </label>
                <select class="form-control iran_yekan select_picker @error('holidays') is-invalid @enderror" @if(!isset($old_data["holidays"])) disabled @endif title="انتخاب کنید" multiple data-size="10" data-live-search="true" id="holidays" name="holidays[]">
                    @if(isset($old_data["holidays"]))
                        @forelse($old_data["holidays"] as $holiday)
                            <option selected value="{{$holiday}}">{{$holiday}}</option>
                        @empty
                        @endforelse
                    @endif
                </select>
                @error('holidays')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="work_shift_id">شیفت کاری</label>
                <select class="form-control iran_yekan select_picker @error('work_shift_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="work_shift_id" name="work_shift_id">
                    @forelse($work_shifts as $shift)
                        <option @if(isset($old_data["work_shift_id"]) && $shift->id == $old_data["work_shift_id"]) selected @endif value="{{$shift->id}}">{{$shift->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('work_shift_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2 align-self-end">
                <button class="btn btn-outline-secondary iran_yekan @error('staff_id') mb-4 @enderror @error('year') mb-4 @enderror @error('month') mb-4 @enderror @error('work_shift_id') mb-4 @enderror" style="height: 30px">
                    <i class="fa fa-search"></i>
                    جستجو
                </button>
            </div>
        </div>
    </form>
    <div class="form-row border rounded pb-2 mt-5">
        <div class="col-12 position-relative form_label_container">
            <h6 class="iran_yekan m-0 text-muted form_label">نتیجه گزارش</h6>
        </div>

        <div class="col-12 mb-3 mt-3 iran_yekan">
            <span class="p-1 border" style="background: #FFFFFF">عادی</span>
            <span class="p-1 border" style="background: #e9e9e9">تعطیل</span>
            <span class="p-1 border" style="background: #FFFFAB">غیبت</span>
            <span class="p-1 border" style="background: #D3FFB5">مرخصی</span>
            <span class="p-1 border" style="background: #fccfcf">خطا</span>
        </div>
        <div class="table-responsive smaller col-12">
            <table class="table table-bordered iran_yekan index_table" id="main_table" data-filter='[1,2,3,4,5]'>
                <thead class="thead-bg-color">
                <tr>
                    <th scope="col">تاریخ</th>
                    <th scope="col">روز هفته</th>
                    <th scope="col">حضور/غیاب</th>
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
                            <tr style="background: {{$result["color"]}}">
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
                            <tr style="background: {{$result["color"]}}">
                                <td><span>{{$result["date"]}}</span></td>
                                <td><span>{{$result["day"]}}</span></td>
                                <td colspan="9">{{$result["err_message"]}}</td>
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
                <table class="table table-dark table-bordered font-size-header iran_yekan">
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
                    @if(isset($old_data))
                        <tr>
                            <td colspan="12">
                                <form target="_blank" action="{{route("SalaryReport.print")}}" method="get">
                                    @csrf
                                    <input type="hidden" name="staff_id" value="{{$old_data["staff_id"]}}">
                                    <input type="hidden" name="from_date" value="{{$old_data["from_date"]}}">
                                    <input type="hidden" name="to_date" value="{{$old_data["to_date"]}}">
                                    @if(isset($old_data["holidays"]))
                                        <select hidden name="holidays[]">
                                            @forelse($old_data["holidays"] as $holiday)
                                                <option value="{{$holiday}}">{{$holiday}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    @endif
                                    <input type="hidden" name="work_shift_id" value="{{$old_data["work_shift_id"]}}">
                                    <button class="btn btn-secondary">
                                        <i class="fa fa-print" style="font-size: 1.47rem"></i>
                                        چاپ گزارش
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

