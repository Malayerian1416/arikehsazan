@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش حضور و غیاب</span>
@endsection
@section('content')
    @can('create','Attendances')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف حضور و غیاب جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Attendances.store")}}" method="post" data-type="attendance" v-on:submit="submit_form">
                    @csrf
                    <input type="hidden" value="" id="type" name="type">
                    <div class="form-row">
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="location_id">
                                موقعیت مکانی
                                <strong class="red_color">*</strong>
                            </label>
                            <select class="form-control iran_yekan select_picker @error('location_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="location_id" name="location_id">
                                @forelse($locations as $location)
                                    <option @if(old("location_id") == $location->id) selected @endif value="{{$location->id}}">{{$location->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('location_id')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="staff_id">کارمند</label>
                            <select class="form-control iran_yekan select_picker @error('staff_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="staff_id" name="staff_id">
                                @forelse($users as $user)
                                    <option @if(old("staff_id") == $user->id) selected @endif value="{{$user->id}}">{{$user->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('staff_id')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="date">تاریخ</label>
                            <input type="text" class="form-control iran_yekan text-center persian_date @error('date') is-invalid @enderror" readonly autocomplete="off" id="date" name="date" value="{{old("date")}}">
                            @error('date')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="time">زمان</label>
                            <input type="time" class="form-control iran_yekan text-center @error('time') is-invalid @enderror" id="time" name="time" value="{{old("time")}}">
                            @error('time')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-12 text-center">
                        <button type="submit" data-value="presence" class="btn btn-outline-success iran_yekan mr-2 submit_button" v-on:click="add_value_to_input">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-sign-in-alt button_icon"></i>
                            <span v-show="button_not_loading">ثبت ورود</span>
                        </button>
                        <button type="submit" data-value="absence" class="btn btn-outline-success iran_yekan mr-2 submit_button" v-on:click="add_value_to_input">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-sign-out-alt button_icon"></i>
                            <span v-show="button_not_loading">ثبت خروج</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام کارمند" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">کارمند</th>
                <th scope="col">موقعیت</th>
                <th scope="col">نوع</th>
                <th scope="col">سال</th>
                <th scope="col">ماه</th>
                <th scope="col">روز</th>
                <th scope="col">ساعت</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                @can('edit','Attendances')
                    <th scope="col">ویرایش</th>
                @endcan
                @can('destroy','Attendances')
                    <th scope="col">حذف</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td><span>{{$attendance->id}}</span></td>
                    <td><span>{{$attendance->staff->name}}</span></td>
                    <td><span>{{$attendance->location->name}}</span></td>
                    <td>
                        <span>
                            @if($attendance->type == "presence")
                                ورود
                            @elseif($attendance->type == "absence")
                                خروج
                            @else
                                نامشخص
                            @endif
                        </span>
                    </td>
                    <td><span>{{$attendance->year}}</span></td>
                    <td><span>{{$attendance->month}}</span></td>
                    <td><span>{{$attendance->day}}</span></td>
                    <td><span>{{$attendance->time}}</span></td>
                    <td><span>{{$attendance->user->name}}</span></td>
                    <td><span>{{verta($attendance->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($attendance->updated_at)->format("Y/n/d")}}</span></td>
                    @can('edit','Attendances')
                        <td>
                            <a class="index_action" href="{{route("Attendances.edit",$attendance->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                        </td>
                    @endcan
                    @can('destroy','Attendances')
                        <td>
                            <form id="delete_form_{{$attendance->id}}" class="d-inline-block" action="{{route("Attendances.destroy",$attendance->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <button class="index_form_submit_button" form="delete_form_{{$attendance->id}}" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        </td>
                    @endcan
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
