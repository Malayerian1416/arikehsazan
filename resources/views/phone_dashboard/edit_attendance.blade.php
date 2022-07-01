@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش حضور و غیاب ".$attendance->staff->name}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("Attendances.update",$attendance->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
        <input type="hidden" value="" id="type" name="type">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="type">نوع</label>
                <input type="text" class="form-control iran_yekan text-center" readonly autocomplete="off" value="@if($attendance->type == 'presence') {{"ورود"}} @elseif($attendance->type == "absence") {{"خروج"}} @else {{"نامشخص"}} @endif">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="location_id">
                    موقعیت مکانی
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan select_picker @error('location_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="location_id" name="location_id">
                    @forelse($locations as $location)
                        <option @if($attendance->location_id == $location->id) selected @endif value="{{$location->id}}">{{$location->name}}</option>
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
                        <option @if($attendance->staff_id == $user->id) selected @endif value="{{$user->id}}">{{$user->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('staff_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="date">تاریخ</label>
                <input type="text" class="form-control iran_yekan text-center persian_date @error('date') is-invalid @enderror" readonly autocomplete="off" id="date" name="date" value="{{verta($attendance->timestamp)->format("Y/n/d")}}">
                @error('date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="time">زمان</label>
                <input type="time" class="form-control iran_yekan text-center @error('time') is-invalid @enderror" id="time" name="time" value="{{$attendance->time}}">
                @error('time')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="update_form" data-value="presence" class="btn btn-outline-success iran_yekan mr-2 submit_button" v-on:click="add_value_to_input">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-sign-in-alt button_icon"></i>
            <span v-show="button_not_loading">ثبت ورود</span>
        </button>
        <button type="submit" form="update_form" data-value="absence" class="btn btn-outline-success iran_yekan mr-2 submit_button" v-on:click="add_value_to_input">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-sign-out-alt button_icon"></i>
            <span v-show="button_not_loading">ثبت خروج</span>
        </button>
        <a href="{{route("Attendances.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
    </div>
@endsection
