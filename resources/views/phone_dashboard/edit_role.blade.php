@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش سمت ".$role->name}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("Roles.update",$role->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
        <div class="form-row" style="height: calc(100vh - 150px);overflow-y: auto">
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="name">
                    عنوان سمت
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$role->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            @forelse($menu_headers as $menu_header)
                @if($menu_header->items->isNotEmpty())
                    <div class="form-group col-md-12 col-lg-3 col-xl-2  iran_yekan mt-3">
                        <label style="font-size: 14px;font-weight: 700" class="col-form-label">{{$menu_header->name}}</label>
                        <ul class="menu_list main_menu_list border">
                            <li>
                                <div class="w-100 d-flex flex-row align-items-center justify-content-around">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" style="width: 49%" v-on:click="select_all_checkboxes">انتخاب همه</button>
                                    <div style="width: 1%"></div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" style="width: 49%" v-on:click="deselect_all_checkboxes">انتخاب هیچکدام</button>
                                </div>
                            </li>
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
                                                        <input @if($role->menu_items->where("pivot.menu_item_id",$menu_item->id)->where("pivot.menu_action_id",$action->id)->first()) checked @endif type="checkbox" name="role_menu[]" value="{{$menu_item->id."#".$action->id."#".$menu_item->route.".".$action->action}}" v-on:click="check_menu_action">
                                                        <span>{{$action->name}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if($menu_item->children)
                                            <ul class="menu_list">
                                                @foreach($menu_item->children as $child)
                                                    <li>
                                                <span style="font-weight: 500;font-size: 13px;color: #1a5d2b">
                                                    <i class="fa fa-bullseye"></i>
                                                    {{$child->name}}
                                                </span>
                                                        @if($child->actions)
                                                            <ul class="menu_list sub_menu_list">
                                                                @foreach($child->actions as $action)
                                                                    <li class="menu_item_selectable" v-on:click="check_menu_action">
                                                                        <input @if($role->menu_items->where("pivot.menu_item_id",$child->id)->where("pivot.menu_action_id",$action->id)->first()) checked @endif type="checkbox" name="role_menu[]" value="{{$child->id."#".$action->id."#".$child->route.".".$action->action}}" v-on:click="check_menu_action">
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
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("Roles.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>

    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="live_data_adding_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="live_data_adding_modal_title">@{{live_data_adding_modal_title}}</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">@{{live_data_adding_label}}</label>
                            <input type="text" class="form-control" v-model="live_data_adding_value">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" v-on:click="live_data_adding_submit">ارسال و ذخیره</button>
                </div>
            </div>
        </div>
    </div>
@endsection
