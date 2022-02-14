@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    ایجاد سرفصل منوی جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("MenuHeaders.store")}}" method="post" v-on:submit="submit_create_form">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <input type="checkbox" class="" id="is_admin" name="is_admin" value="1">
                <label class="col-form-label iran_yekan black_color" for="is_admin">
                    متعلق به مدیر سامانه
                </label>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام گروه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="slug">برچسب</label>
                <input type="text" class="form-control iran_yekan text-center @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{old("contract_row")}}">
                @error('slug')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="icon_id">آیکون</label>
                <select class="form-control iran_yekan text-center select_picker @error('icon_id') is-invalid @enderror" data-live-search="true" id="icon_id" name="icon_id" title="انتخاب کنید" data-size="20">
                    @forelse($icons as $icon)
                        <option value="{{$icon->id}}" data-icon="{{$icon->name}}">{{$icon->name}}</option>
                    @empty
                        <option>آیکونی وجود ندارد</option>
                    @endforelse
                </select>
                @error('icon_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
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
        <a href="{{route("MenuHeaders.index")}}" class="index_action">
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
