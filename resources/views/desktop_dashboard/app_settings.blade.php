@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    تنظیمات برنامه
@endsection
@section('content')
    <form id="update_form" data-type="update" action="{{route("AppSettings.update",$company_information->id)}}" method="post" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام شرکت
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$company_information->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="ceo_user_id">
                    مدیرعامل
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('ceo_user_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="ceo_user_id" name="ceo_user_id">
                    @forelse($users as $user)
                        <option @if($company_information->ceo_user_id == $user->id || old("ceo_user_id") == $user->id)  selected @endif value="{{$user->id}}">{{$user->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('ceo_user_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="description">
                    توضیحات
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('description') is-invalid @enderror" id="description" name="description" value="{{$company_information->description}}">
                @error('description')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="address">
                    آدرس
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('address') is-invalid @enderror" id="address" name="address" value="{{$company_information->address}}">
                @error('address')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="phone">
                    تلفن
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('phone') is-invalid @enderror masked" data-mask="000-00000000" id="phone" name="phone" value="{{$company_information->phone}}">
                @error('phone')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="app_ver">
                    نسخه برنامه
                    <strong class="red_color">*</strong>
                </label>
                <input type="number" class="form-control iran_yekan text-center @error('app_ver') is-invalid @enderror" step=".01" id="app_ver" name="app_ver" value="{{$company_information->app_ver}}">
                @error('app_ver')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="logo">لوگو</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('logo') is-invalid @enderror" v-on:change="file_browser_change" id="logo" name="logo" accept=".jpg,.png,.bmp,.jpeg">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('logo')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
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
