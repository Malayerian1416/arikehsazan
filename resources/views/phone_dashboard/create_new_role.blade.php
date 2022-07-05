@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">ایجاد سمت جدید</span>
@endsection
@section('content')
    <form id="create_form" action="{{route("Roles.store")}}" method="post" data-type="create" v-on:submit="submit_form">
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
                @if($menu_header->items->isNotEmpty())
                    <div class="form-group col-md-12 col-lg-4 col-xl-3 iran_yekan mt-3">
                        <label style="font-size: 14px;font-weight: 700" class="col-form-label">{{$menu_header->name}}</label>
                        <ul class="menu_list main_menu_list border">
                            @forelse($menu_header->items as $menu_item)
                                @if($menu_item->parent_id == null)
                                    <li class="lev1_menu_list">
                                <span style="font-weight: 600;font-size: 14px;color: #1a5d2b">
                                    <i class="fa fa-bullseye"></i>
                                    {{$menu_item->name}}
                                </span>
                                        @if($menu_item->actions)
                                            <ul class="menu_list sub_menu_list">
                                                @foreach($menu_item->actions as $action)
                                                    <li class="menu_item_selectable" v-on:click="check_menu_action">
                                                        <input type="checkbox" name="role_menu[]" value="{{$menu_item->id."#".$action->id."#".$menu_item->route.".".$action->action}}" v-on:click="check_menu_action">
                                                        <span>{{$action->name}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if($menu_item->children)
                                            <ul class="menu_list">
                                                @foreach($menu_item->children as $child)
                                                    <li>
                                                <span style="font-weight: 500;font-size: 13px;color: #2a733c">
                                                    <i class="fa fa-bullseye"></i>
                                                    {{$child->name}}
                                                </span>
                                                        @if($child->actions)
                                                            <ul class="menu_list sub_menu_list">
                                                                @foreach($child->actions as $action)
                                                                    <li class="menu_item_selectable" v-on:click="check_menu_action">
                                                                        <input type="checkbox" name="role_menu[]" value="{{$menu_item->id."#".$action->id."#".$child->route.".".$action->action}}" v-on:click="check_menu_action">
                                                                        <span>{{$action->name}}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    @error('name')
                    <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                    @enderror
                @endif
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

    </div>
@endsection
