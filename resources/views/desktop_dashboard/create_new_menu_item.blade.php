@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}" defer></script>
@endsection
@section('page_title')
    ایجاد عنوان فرعی منو
@endsection
@section('content')
    <form id="create_form" action="{{route("MenuItems.store")}}" method="post" v-on:submit="submit_create_form">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="menu_title_id">منوی اصلی</label>
                <select class="form-control iran_yekan text-center select_picker @error('menu_title_id') is-invalid @enderror" data-live-search="true" id="menu_title_id" name="menu_title_id" title="انتخاب کنید" data-size="20">
                    @forelse($menu_titles as $menu_title)
                        <option value="{{$menu_title->id}}">{{$menu_title->name." (".$menu_title->menu_header->name.")"}}</option>
                    @empty
                        <option>منوی اصلی وجود ندارد</option>
                    @endforelse
                </select>
                @error('menu_title_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="menu_action_id">عملیات وابسته</label>
                <select class="form-control iran_yekan text-center select_picker @error('menu_action_id') is-invalid @enderror" v-on:change="main_route_change" multiple data-live-search="true" id="menu_action_id" name="menu_action_id[]" title="انتخاب کنید" data-size="20">
                    @forelse($menu_actions as $menu_action)
                        <option value="{{$menu_action->id}}">{{$menu_action->name}}</option>
                    @empty
                        <option>عملیاتی وجود ندارد</option>
                    @endforelse
                </select>
                @error('menu_action_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="main">عنوان اصلی</label>
                <select class="form-control iran_yekan text-center select_picker @error('main') is-invalid @enderror" data-live-search="true" id="main" name="main" title="انتخاب کنید" data-size="20">

                </select>
                @error('main')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <input type="checkbox" id="notifiable" name="notifiable" value="1">
                <label class="col-form-label iran_yekan black_color" for="main">اطلاع رسانی بلادرنگ</label>
                <input type="text" class="form-control" name="notification_channel">
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
        <a href="{{route("MenuItems.index")}}" class="index_action">
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
