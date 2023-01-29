@extends('phone_dashboard.p_dashboard')
@section('styles')
    <style>
        .leave_date_table{
            max-width: 100%;
        }
        .leave_date_table th{
            text-align: center;
            color: #FFFFFF;
        }
        .leave_date_table td,.leave_date_table th{
            padding: 0.3rem;
        }
        .leave_date_table tbody tr:first-child td{
            border-top: none;
        }
        .leave_date_table tbody tr td:last-child{
            border-left: none;
        }
        .calender_day{
            cursor: pointer;
        }
        .day_off{
            cursor: not-allowed;
        }
        .calender_day .cover{
            display: none;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            transition: all 0.2s linear;
        }
        .cover.active {
            display: flex;
        }
    </style>
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">درخواست مرخصی روزانه و ویرایش</span>
@endsection
@section('content')
    @can('create','DailyLeaves')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">درخواست جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("DailyLeaves.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row pb-2">
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
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="father_name">
                                انتخاب روز
                                <strong class="red_color">*</strong>
                            </label>
                            <div class="text-center">
                                <table class="table table-bordered iran_yekan leave_date_table @error('selected_dates') is-invalid @enderror">
                                    <thead class="bg-dark">
                                    <tr>
                                        <th class="text-center">شنبه</th>
                                        <th class="text-center">یکشنبه</th>
                                        <th class="text-center">دوشنبه</th>
                                        <th class="text-center">سه شنبه</th>
                                        <th class="text-center">چهارشنبه</th>
                                        <th class="text-center">پنجشنبه</th>
                                        <th class="text-center">جمعه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($calender as $week)
                                        <tr>
                                            @foreach($week as $day)
                                                @if($day["day"] != "" && $day["day_off"] == 0)
                                                    <td class="calender_day position-relative bg-success" v-on:click="calender_selection">
                                                        <div class="cover">
                                                            <i class="fa fa-check fa-3x white_color"></i>
                                                            <input hidden type="checkbox" name="selected_dates[]" value="{{$day["year"]."/".$day["month"]."/".$day["day"]}}">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h2>{{$day["day"]}}</h2>
                                                            <h4 class="text-light">{{$day["month_name"]}}</h4>
                                                            <h6 class="text-light">{{$day["year"]}}</h6>
                                                        </div>
                                                    </td>
                                                @elseif($day["day"] != "" && $day["day_off"] == 1)
                                                    <td class="calender_day day_off position-relative bg-danger">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h2 class="text-muted-light">{{$day["day"]}}</h2>
                                                            <h4 class="text-muted-light">{{$day["month_name"]}}</h4>
                                                            <h6 class="text-muted-light">{{$day["year"]}}</h6>
                                                        </div>
                                                    </td>
                                                @elseif($day["day"] != "" && $day["day_off"] == 2)
                                                    <td class="calender_day day_off position-relative bg-warning">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h2 class="text-muted">{{$day["day"]}}</h2>
                                                            <h4 class="text-muted">{{$day["month_name"]}}</h4>
                                                            <h6 class="text-muted">{{$day["year"]}}</h6>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="bg-secondary"></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @error('selected_dates')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="leave_docs">اسکن مدارک</label>
                            <input type="file" hidden class="form-control iran_yekan text-center @error('leave_docs') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="leave_docs[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                            <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                            @error('leave_docs')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-12 text-center pt-3">
                        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                            <span v-show="button_not_loading">ارسال و ذخیره</span>
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
                <th scope="col">روز(ها)</th>
                <th scope="col">توسط</th>
                <th scope="col">وضعیت</th>
                <th scope="col">موقعیت</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ارسال</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($daily_leaves as $leave)
                <tr>
                    <td><span>{{$leave->id}}</span></td>
                    <td>
                        @forelse($leave->days as $day)
                            <span>{{"$day->year/$day->month/$day->day"}} @if(!$loop->last) , @endif</span>
                        @empty
                        @endforelse
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
                            @else
                                {{"تکمیل شده"}}
                            @endif
                        </span>
                    </td>
                    <td><span>{{verta($leave->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($leave->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        @if($leave->automation->previous_role_id == 0)
                            <a class="index_action" role="button" href="{{route("DailyLeaves.edit",$leave->id)}}">
                                <i class="fa fa-pen index_edit_icon"></i>
                            </a>
                        @else
                            <i class="fa fa-times red_color"></i>
                        @endif
                    </td>
                    <td>
                        @if($leave->automation->previous_role_id == 0)
                            <form id="delete_form_{{$leave->id}}" class="d-inline-block" action="{{route("DailyLeaves.destroy",$leave->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
@endsection
