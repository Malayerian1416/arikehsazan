@extends('desktop_dashboard.d_dashboard')
@section('scripts')
    <script>
        $(document).ready(function (){
            @if(isset($locations))
            const locations = @json($locations);
            let options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            };
            if (navigator.geolocation) {
                $("#location_text").text("در حال یافتن موقعیت فعلی...");
                $("#location_loading").show();
                navigator.geolocation.getCurrentPosition(position => {
                    $(".submit_button").prop("disabled",false);
                    locations.forEach(function (location){
                        if(d3.geoContains(JSON.parse(location["geoJson"]),[position.coords.longitude, position.coords.latitude])) {
                            $("#location_name").val(location["name"]);
                            $("#location_text").text("موقعیت فعلی");
                            $("#location_loading").hide();
                            $("#location_id").val(location["hash"]);
                        }
                    });
                },() => {
                    $(".submit_button").prop("disabled",false);
                    $("#location_text").text("موقعیت فعلی");
                    $("#location_loading").hide();
                    $("#location_name").val("موقعیت مکانی شما مشخص نمی باشد");
                },options);
            }
            else {
                $(".submit_button").prop("disabled",false);
                $("#location_name").val("مرورگر شما از سیستم ناوبری پشتیبانی نمی کند");
            }
            @endif
            @if(isset($current_leave))
            const hour_handler = document.getElementById("hour_handler");
            const minute_handler = document.getElementById("minute_handler");
            const second_handler = document.getElementById("second_handler");
            let second = parseInt(second_handler.innerText);
            let minute = parseInt(minute_handler.innerText);
            let hour = parseInt(hour_handler.innerText);
            setInterval(function () {
                if(second === 59) {
                    second = 0;
                    if(minute === 59) {
                        minute = 0
                        if(hour === 23)
                            hour = 0;
                        else
                            ++hour;
                    }
                    else
                        ++minute
                }
                else
                    ++second;
                second <= 9 ? second_handler.innerText = "0" + second.toString() : second_handler.innerText = second.toString();
                minute <= 9 ? minute_handler.innerText = "0" + minute.toString() : minute_handler.innerText = minute.toString();
                hour <= 9 ? hour_handler.innerText = "0" + hour.toString() : hour_handler.innerText = hour.toString();
            },1000);
            @endif
        });
    </script>
@endsection
@section('page_title')
    درخواست مرخصی ساعتی و ویرایش
@endsection
@section('content')
    @if(isset($current_leave))
        <div class="w-75 p-2 m-auto">
            <div class="w-100 p-2 d-flex flex-row-reverse justify-content-center align-items-end">
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="bg-info text-center p-2 white_color" style="width: 161px">شروع مرخصی</h5>
                        <div class="d-flex flex-row-reverse justify-content-center align-items-center iran_yekan">
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{verta($current_leave->departure)->format("H")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{verta($current_leave->departure)->format("i")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{"00"}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="text-center p-2">
                            <i class="fa fa-arrow-right fa-2x"></i>
                        </h5>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="bg-primary text-center p-2 white_color" style="width: 161px">زمان فعلی</h5>
                        <div class="d-flex flex-row-reverse justify-content-center align-items-center iran_yekan">
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 id="hour_handler" class="m-0 yellow_color">{{verta()->format("H")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 id="minute_handler" class="m-0 yellow_color">{{verta()->format("i")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 id="second_handler" class="m-0 yellow_color text-center">{{verta()->format("s")}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="text-center p-2">
                            <i class="fa fa-arrow-right fa-2x"></i>
                        </h5>
                    </div>
                @if($current_leave->arrival)
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="bg-success text-center p-2 white_color" style="width: 161px">پایان مرخصی</h5>
                        <div class="d-flex flex-row-reverse justify-content-center align-items-center iran_yekan">
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{verta($current_leave->arrival)->format("H")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{verta($current_leave->arrival)->format("i")}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{"00"}}</h1>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="d-flex flex-column justify-content-center align-items-center iran_yekan">
                        <h5 class="bg-success text-center p-2 white_color" style="width: 161px">پایان مرخصی</h5>
                        <div class="d-flex flex-row-reverse justify-content-center align-items-center iran_yekan">
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{"??"}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark ml-2" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{"??"}}</h1>
                            </div>
                            <div class="d-flex flex-row justify-content-center align-items-center p-2 bg-dark" style="min-height: 60px;min-width: 60px;border-radius: 5px">
                                <h1 class="m-0 yellow_color">{{"??"}}</h1>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <form class="w-100 text-center" id="attendance_form" action="{{route("hourly_leave_register_attendance",$current_leave->id)}}" method="post" data-type="attendance" v-on:submit="submit_form">
                @csrf
                @method('put')
                <div class="w-100 d-flex flex-column align-items-center justify-content-center iran_yekan p-2">
                    <input type="hidden" id="type" value="presence">
                    <div class="w-100 text-center">
                        <label class="col-form-label iran_yekan black_color" for="location_id">
                            <i id="location_loading" class="fa fa-spinner fa-spin fa-1_2x" style="display: none"></i>
                            <span id="location_text">موقعیت فعلی</span>
                        </label>
                        <input type="text" id="location_name" readonly class="form-control iran_yekan m-auto text-center @error('location_id') is-invalid @enderror" style="max-width: 640px">
                        <input type="hidden" id="location_id" readonly name="location_id">
                        @error('location_id')
                        <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <button form="attendance_form" disabled class="btn btn-lg btn-outline-success mt-4 mb-3 submit_button">
                        <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                        <i v-show="button_not_loading" class="fa fa-sign-in-alt button_icon"></i>
                        <span v-show="button_not_loading">ثبت ورود و اتمام مرخصی</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        @can('create','HourlyLeaves')
            <div class="row pt-1 pb-3">
                <div class="col-12 hide_section_container">
                    <button class="btn btn-outline-success">
                        <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                        <span class="iran_yekan hide_section_title">درخواست جدید</span>
                    </button>
                </div>
                <div class="col-12 hide_section @if($errors->any()) active @endif">
                    <form id="create_form" action="{{route("HourlyLeaves.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row pb-2">
                            <div class="alert alert-warning w-100 iran_yekan mt-4" role="alert">
                                <p class="text-justify m-0">
                                    <i class="fa fa-info-circle fa-1_6x mr-2"></i>
                                    در صورت وارد نکردن زمان شروع و پایان، پس از ارسال درخواست، زمان شروع مرخصی مطابق با زمان فعلی و زمان ثبت ورود مجدد شما به موقعیت مورد نظر، به عنوان زمان پایان مرخصی لحاظ میگردد.
                                </p>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label iran_yekan black_color" for="reason">
                                    لطفا علت درخواست مرخصی را به طور مختصر شرح دهید
                                    <strong class="red_color">*</strong>
                                </label>
                                <textarea type="text" class="form-control iran_yekan text-center @error('reason') is-invalid @enderror" id="reason" name="reason">{{old("reason")}}</textarea>
                                @error('reason')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                                <label class="col-form-label iran_yekan black_color" for="location_id">
                                    <i id="location_loading" class="fa fa-spinner fa-spin" style="display: none"></i>
                                    <span id="location_text">موقعیت فعلی</span>
                                </label>
                                <input type="text" id="location_name" readonly class="form-control iran_yekan">
                                <input type="hidden" id="location_id" name="location_id">
                            </div>
                            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                                <label class="col-form-label iran_yekan black_color" for="departure">
                                    شروع مرخصی
                                </label>
                                <input type="time" name="departure" value="{{old("departure")}}" class="form-control text-center @error('departure') is-invalid @enderror" style="height: 30px">
                                @error('departure')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                                <label class="col-form-label iran_yekan black_color" for="arrival">
                                    پایان مرخصی
                                </label>
                                <input type="time" name="arrival" value="{{old("arrival")}}" class="form-control text-center @error('arrival') is-invalid @enderror" style="height: 30px">
                                @error('arrival')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                                <label class="col-form-label iran_yekan black_color" for="leave_docs">اسکن مدارک</label>
                                <input type="file" hidden class="form-control iran_yekan text-center @error('leave_docs') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="leave_docs[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                                @error('leave_docs')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-12 text-center pt-3">
                            <button disabled type="submit" form="create_form" class="btn btn-outline-success iran_yekan submit_button">
                                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                <i v-show="button_not_loading" class="fa fa-save button_icon"></i>
                                <span v-show="button_not_loading">ارسال درخواست</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
        <div class="row pt-1 pb-3">
            <div class="col-12">
                <div class="alert alert-warning w-100 iran_yekan" role="alert">
                    <p class="text-justify m-0" style="font-size: 10px">
                        <i class="fa fa-info-circle fa-1_6x mr-2"></i>
                        ویرایش و یا حذف درخواست مرخصی ارسال شده، پس از ارجاع توسط مسئول رسیدگی و تایید درخواست امکان پذیر می باشد.
                    </p>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[2,3,4]'>
                <thead class="thead-bg-color">
                <tr>
                    <th scope="col">شماره</th>
                    <th scope="col">مکان</th>
                    <th scope="col">خروج</th>
                    <th scope="col">ورود</th>
                    <th scope="col">توسط</th>
                    <th scope="col">وضعیت</th>
                    <th scope="col">موقعیت</th>
                    <th scope="col">ویرایش</th>
                    <th scope="col">حذف</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hourly_leaves as $leave)
                    <tr>
                        <td><span>{{$leave->id}}</span></td>
                        <td>
                        <span>
                            @if($leave->location)
                                {{$leave->location->name}}
                            @else
                                {{"نامشخص"}}
                            @endif
                        </span>
                        </td>
                        <td><span>{{$leave->departure}}</span></td>
                        <td>
                        <span>
                            @if($leave->arrival)
                                {{$leave->arrival}}
                            @else
                                {{"??:??"}}
                            @endif
                        </span>
                        </td>
                        <td><span >{{$leave->user->name}}</span></td>
                        <td>
                        <span>
                            @if($leave->automation->is_finished)
                                @if($leave->is_approved)
                                    {{"تایید شده"}}
                                @else
                                    {{"تایید نشده"}}
                                @endif
                            @else
                                {{"در جریان"}}
                            @endif
                        </span>
                        </td>
                        <td>
                        <span>
                            @if($leave->automation->current_role_id <> 0)
                                {{\App\Models\Role::query()->findOrFail($leave->automation->current_role_id)->name}}
                            @elseif($leave->automation->is_finished)
                                {{"تکمیل شده"}}
                            @else
                                {{"ارجاع شده"}}
                            @endif
                        </span>
                        </td>
                        <td>
                            @if($leave->automation->previous_role_id == 0)
                                <a class="index_action" role="button" href="{{route("HourlyLeaves.edit",$leave->id)}}">
                                    <i class="fa fa-pen index_edit_icon"></i>
                                </a>
                            @else
                                <i class="fa fa-times red_color"></i>
                            @endif
                        </td>
                        <td>
                            @if($leave->automation->previous_role_id == 0)
                                <form id="delete_form_{{$leave->id}}" class="d-inline-block" action="{{route("HourlyLeaves.destroy",$leave->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                    @csrf
                                    @method('delete')
                                    <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                                </form>
                            @else
                                <i class="fa fa-times red_color"></i>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9">اطلاعاتی وجود ندارد</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
@section('page_footer')
    <div class="form-row text-center p-3 d-flex flex-row justify-content-center">
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>بستن</span>
            </button>
        </a>
    </div>
@endsection
@section('modal_alerts')
@endsection
