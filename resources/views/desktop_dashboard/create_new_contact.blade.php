@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    ایجاد مخاطب جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Phonebook.store")}}" method="post" data-type="create" v-on:submit="submit_form">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="phone_number_1">شماره تماس 1</label>
                <input type="text" class="form-control iran_yekan text-center @error('phone_number_1') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_1" name="phone_number_1" value="{{old("phone_number_1")}}">
                @error('phone_number_1')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="phone_number_2">شماره تماس 2</label>
                <input type="text" class="form-control iran_yekan text-center @error('phone_number_2') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_2" name="phone_number_2" value="{{old("phone_number_2")}}">
                @error('contract_row')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="phone_number_3">شماره تماس 3</label>
                <input type="text" class="form-control iran_yekan text-center @error('phone_number_3') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_3" name="phone_number_3" value="{{old("phone_number_3")}}">
                @error('phone_number_3')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="job_title">عنوان شغل</label>
                <input type="text" class="form-control iran_yekan text-center @error('job_title') is-invalid @enderror" id="job_title" name="job_title" value="{{old("job_title")}}">
                @error('job_title')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="email">ایمیل</label>
                <input type="text" class="form-control iran_yekan text-center @error('email') is-invalid @enderror" id="email" name="email" value="{{old("email")}}">
                @error('email')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-6">
                <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                <input type="text" class="form-control iran_yekan text-center" id="address" name="address" value="{{old("address")}}">
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="note">یادداشت</label>
                <textarea type="text" class="form-control iran_yekan text-center" id="note" name="note">
                    {{old("note")}}
                </textarea>
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ذخیره</span>
        </button>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
