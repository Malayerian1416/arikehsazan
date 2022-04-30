@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    {{"ثبت حضور و غیاب "}}
@endsection
@section('content')
    <form id="update_form" action="{{route("RegisterAttendance.register")}}" method="post" v-on:submit="submit_attendance_register_form">
        @csrf
        <input type="hidden" value="" id="type" name="type">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="location_id">
                    موقعیت مکانی
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan select_picker @error('location_id') is-invalid @enderror" required title="انتخاب کنید" data-size="10" data-live-search="true" id="location_id" name="location_id" v-model="location_id">
                    @forelse($locations as $location)
                        <option @if(old("location_id") == $location->id) selected @endif value="{{$location->id}}">{{$location->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('location_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div id="attendance_map" style="width: 100vw;height: 400px;margin: 0"></div>
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
        <a href="{{route("idle")}}">
        <button type="button" class="btn btn-outline-light iran_yekan">
            <i class="fa fa-backspace button_icon"></i>
            <span>خروج</span>
        </button>
        </a>
    </div>
@endsection
