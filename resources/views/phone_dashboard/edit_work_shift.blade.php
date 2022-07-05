@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش نوبت کاری"}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("WorkShifts.update",$shift->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
        <div class="form-row pb-2">
            <div class="form-group col-md-12 col-lg-4 col-xl-12">
                <label class="col-form-label iran_yekan black_color" for="name">
                    عنوان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$shift->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-6">
                <label class="col-form-label iran_yekan black_color" for="father_name">ساعت شروع کار</label>
                <input type="time" class="form-control iran_yekan text-center @error('arrival') is-invalid @enderror" id="arrival" name="arrival" value="{{$shift->arrival}}">
                @error('arrival')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-6">
                <label class="col-form-label iran_yekan black_color" for="father_name">ساعت پایان کار</label>
                <input type="time" class="form-control iran_yekan text-center @error('departure') is-invalid @enderror" id="departure" name="departure" value="{{$shift->departure}}">
                @error('departure')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin ml-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("WorkShifts.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
    </div>
@endsection
