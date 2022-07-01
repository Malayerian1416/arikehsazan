@extends('phone_dashboard.p_dashboard')
@section('styles')
    <style>
        .leave_date_table th{
            text-align: center;
            color: #FFFFFF;
        }
        .leave_date_table td,th{
            padding: 0.25rem;
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
    @can('create','Leaves')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">درخواست جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Contractors.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row pb-2">
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="reason">
                                لطفا علت درخواست مرخصی را به طور مختصر شرح دهید
                                <strong class="red_color">*</strong>
                            </label>
                            <textarea type="text" class="form-control iran_yekan text-center @error('reason') is-invalid @enderror" style="height: 100px;resize: none" id="reason" name="reason">{{old("reason")}}</textarea>
                            @error('reason')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="father_name">
                                انتخاب روز
                                <strong class="red_color">*</strong>
                            </label>
                            <div class="w-100 text-center">
                                <table class="table table-bordered iran_yekan leave_date_table w-100">
                                    <thead class="bg-dark">
                                    <tr>
                                        <th class="text-center">ش</th>
                                        <th class="text-center">ی</th>
                                        <th class="text-center">د</th>
                                        <th class="text-center">س</th>
                                        <th class="text-center">چ</th>
                                        <th class="text-center">پ</th>
                                        <th class="text-center">ج</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($calender as $week)
                                        <tr>
                                            @foreach($week as $day)
                                                @if($day["day"] != "" && $day["day_off"] == 0)
                                                    <td class="calender_day position-relative bg-success" v-on:click="calender_selection">
                                                        <div class="cover">
                                                            <i class="fa fa-check fa-2x white_color"></i>
                                                            <input hidden type="checkbox" name="day[]" value="{{$day["day"]}}">
                                                            <input hidden type="checkbox" name="month[]" value="{{$day["month"]}}">
                                                            <input hidden type="checkbox" name="year[]" value="{{$day["year"]}}">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h4>{{$day["day"]}}</h4>
                                                            <h6 class="text-light">{{$day["month_name"]}}</h6>
                                                            <h6 class="text-light">{{$day["year"]}}</h6>
                                                        </div>
                                                    </td>
                                                @elseif($day["day"] != "" && $day["day_off"] == 1)
                                                    <td class="calender_day day_off position-relative bg-danger">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h4 class="text-muted-light">{{$day["day"]}}</h4>
                                                            <h6 class="text-muted-light">{{$day["month_name"]}}</h6>
                                                            <h6 class="text-muted-light">{{$day["year"]}}</h6>
                                                        </div>
                                                    </td>
                                                @elseif($day["day"] != "" && $day["day_off"] == 2)
                                                    <td class="calender_day day_off position-relative bg-warning">
                                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                                            <h4 class="text-muted">{{$day["day"]}}</h4>
                                                            <h6 class="text-muted">{{$day["month_name"]}}</h6>
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
                            </div>
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
                    <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پیمانکار، کد ملی و یا تلفن همراه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[2,3,4]'>
                    <thead class="thead-bg-color">
                    <tr>
                        <th scope="col">شماره</th>
                        <th scope="col">نوع</th>
                        <th scope="col">نام</th>
                        <th scope="col">کد ملی</th>
                        <th scope="col">تلفن همراه</th>
                        <th scope="col">مدارک</th>
                        <th scope="col">تاریخ ثبت</th>
                        <th scope="col">تاریخ ویرایش</th>
                        <th scope="col">ویرایش</th>
                        <th scope="col">حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--            @forelse($contractors as $contractor)--}}
                    {{--                <tr>--}}
                    {{--                    <td><span>{{$contractor->id}}</span></td>--}}
                    {{--                    <td>--}}
                    {{--                        <span>--}}
                    {{--                            @if($contractor->type == 0)--}}
                    {{--                                پیمانکار--}}
                    {{--                            @else--}}
                    {{--                                کارگر--}}
                    {{--                            @endif--}}
                    {{--                        </span>--}}
                    {{--                    </td>--}}
                    {{--                    <td><span>{{$contractor->name}}</span></td>--}}
                    {{--                    <td><span>{{$contractor->national_code}}</span></td>--}}
                    {{--                    <td><span data-mask="0000-000-0000">{{$contractor->cellphone}}</span></td>--}}
                    {{--                    @if(in_array($contractor->id,$docs))--}}
                    {{--                        <td><a href="{{route("contractor_doc_download",$contractor->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده مدارک"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>--}}
                    {{--                    @else--}}
                    {{--                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>--}}
                    {{--                    @endif--}}
                    {{--                    <td><span>{{verta($contractor->created_at)->format("Y/n/d")}}</span></td>--}}
                    {{--                    <td><span>{{verta($contractor->updated_at)->format("Y/n/d")}}</span></td>--}}
                    {{--                    <td>--}}
                    {{--                        <a class="index_action" href="{{route("Contractors.edit",$contractor->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>--}}
                    {{--                    </td>--}}
                    {{--                    <td>--}}
                    {{--                        <form id="delete_form_{{$contractor->id}}" class="d-inline-block" action="{{route("Contractors.destroy",$contractor->id)}}" method="post" data-type="delete" v-on:submit="submit_form">--}}
                    {{--                            @csrf--}}
                    {{--                            @method('delete')--}}
                    {{--                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>--}}
                    {{--                        </form>--}}
                    {{--                    </td>--}}
                    {{--                </tr>--}}
                    {{--            @empty--}}
                    {{--            @endforelse--}}
                    </tbody>
                </table>
            </div>
        @endsection
        @section('modal_alerts')
        @endsection
