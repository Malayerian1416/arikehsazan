@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}" defer></script>
@endsection
@section('page_title')
    {{"ویرایش عنوان فرعی منو - ".$menu_item->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("MenuItems.update",$menu_item->id)}}" method="post" v-on:submit="submit_update_form">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$menu_item->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="menu_title_id">منوی اصلی</label>
                <select class="form-control iran_yekan text-center select_picker @error('menu_title_id') is-invalid @enderror" data-live-search="true" id="menu_title_id" name="menu_title_id" title="انتخاب کنید" data-size="20">
                    @forelse($menu_titles as $menu_title)
                        <option @if($menu_title->id == $menu_item->menu_title->id) selected @endif value="{{$menu_title->id}}">{{$menu_title->name." (".$menu_title->menu_header->name.")"}}</option>
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
                        <option @if(in_array($menu_action->id,array_column($menu_item->actions->toArray(),"id"))) selected @endif value="{{$menu_action->id}}">{{$menu_action->name}}</option>
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
                    @forelse($menu_item->actions as $menu_action)
                        <option @if($menu_action->action == $menu_item->main_route) selected @endif value="{{$menu_action->id}}">{{$menu_action->name}}</option>
                    @empty
                        <option>عملیاتی وجود ندارد</option>
                    @endforelse
                </select>
                @error('main')
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
