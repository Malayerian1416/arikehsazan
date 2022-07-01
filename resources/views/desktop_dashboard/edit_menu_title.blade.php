@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    {{"ویرایش عنوان اصلی منو - ".$menu_title->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("MenuTitles.update",$menu_title->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$menu_title->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="menu_header_id">گروه منو</label>
                <select class="form-control iran_yekan text-center select_picker @error('menu_header_id') is-invalid @enderror" data-live-search="true" id="menu_header_id" name="menu_header_id" title="انتخاب کنید" data-size="20">
                    @forelse($menu_headers as $menu_header)
                        <option value="{{$menu_header->id}}" @if($menu_header->id == $menu_title->menu_header->id) selected @endif data-icon="{{$menu_header->icon->name}}">{{$menu_header->name}}</option>
                    @empty
                        <option>گروهی وجود ندارد</option>
                    @endforelse
                </select>
                @error('menu_header_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="main_route">
                    مسیر اصلی
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control text-center @error('main_route') is-invalid @enderror ltr" id="main_route" name="main_route" value="{{$menu_title->main_route}}">
                @error('main_route')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="route">
                    مسیر
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('route') is-invalid @enderror" id="route" name="route" value="{{$menu_title->route}}">
                @error('route')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="icon_id">آیکون</label>
                <select class="form-control iran_yekan text-center select_picker @error('icon') is-invalid @enderror" data-live-search="true" id="icon" name="icon" title="انتخاب کنید" data-size="20">
                    @forelse($icons as $icon)
                        <option @if($icon->name == $menu_title->icon) selected @endif value="{{$icon->name}}" data-icon="{{$icon->name}}">{{$icon->name}}</option>
                    @empty
                        <option>آیکونی وجود ندارد</option>
                    @endforelse
                </select>
                @error('icon')
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
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("MenuTitles.index")}}" class="index_action">
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
