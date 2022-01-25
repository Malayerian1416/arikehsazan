@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}" defer></script>
@endsection
@section('page_title')
    ایجاد سمت جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Roles.store")}}" method="post" v-on:submit="submit_create_form">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="name">
                    عنوان سمت
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            @forelse($menu_headers as $menu_header)
                <div class="form-group col-md-12 col-lg-4 col-xl-3  iran_yekan mt-3">
                    <label class="col-form-label iran_yekan black_color" for="name">{{$menu_header->name}}</label>
                    <select class="form-control select_picker @error('role_menu') is-invalid @enderror" multiple title="انتخاب کنید" data-size="20" data-live-search="true" id="role_menu_{{$menu_header->id}}" name="role_menu[]" data-selected-text-format="count" data-actions-box="true">
                        @forelse($menu_header->menu_titles as $menu_title)
                            <optgroup label="{{$menu_title->name}}">
                                @forelse($menu_title->menu_items as $menu_item)
                                    @forelse($menu_item->actions as $action)
                                        <option value="{{$menu_item->id."#".$action->id."#".$menu_title->route.".".$action->action}}">{{$action->name}}</option>
                                    @empty
                                    @endforelse
                                @empty
                                @endforelse
                            </optgroup>
                            <option data-divider="true"></option>
                        @empty
                        @endforelse
                    </select>
                </div>
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            @empty
            @endforelse
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
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
