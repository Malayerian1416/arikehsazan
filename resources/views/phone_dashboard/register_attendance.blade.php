@extends('phone_dashboard.p_dashboard')
@section('scripts')
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">ثبت حضور و غیاب</span>
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
            <div id="attendance_map" style="width: 100vw;height: 200px;margin: 0"></div>
            <div class="form-group col-md-12 d-flex flex-row justify-content-around align-items-center mt-4">
                <button type="submit" form="update_form" style="border-radius: 50%;width: 130px;height: 130px" data-value="presence" class="btn btn-success iran_yekan submit_button" v-on:click="add_value_to_input">
                    <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                    <i v-show="button_not_loading" class="fa fa-sign-in-alt d-block fa-3x"></i>
                    <span v-show="button_not_loading" style="font-size: 14px">ثبت ورود</span>
                </button>
                <button type="submit" form="update_form" style="border-radius: 50%;width: 130px;height: 130px" data-value="absence" class="btn btn-secondary iran_yekan ml-4 submit_button" v-on:click="add_value_to_input">
                    <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                    <i v-show="button_not_loading" class="fa fa-sign-out-alt d-block fa-3x"></i>
                    <span v-show="button_not_loading" style="font-size: 14px">ثبت خروج</span>
                </button>
            </div>
        </div>
    </form>
@endsection
