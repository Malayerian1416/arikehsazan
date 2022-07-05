@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده لیست سمت ها و ویرایش
@endsection
@section('content')
    @if(auth()->user()->is_admin)
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->has(["name","role_menu"]))) active @endif">
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
                                <div class="form-group col-md-12 col-lg-3 col-xl-2 iran_yekan mt-3">
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
                                                                                    <input type="checkbox" name="role_menu[]" value="{{$child->id."#".$action->id."#".$child->route.".".$action->action}}" v-on:click="check_menu_action">
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
                    <div class="form-group col-12 text-center">
                        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                            <span v-show="button_not_loading">ارسال و ذخیره</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @can('create','Roles')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف سمت جدید</span>
                </h6>
            </div>
            <div class="col-12 hide_section @if($errors->has(["name","role_menu"])) active @endif">
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
                                                                                    <input type="checkbox" name="role_menu[]" value="{{$child->id."#".$action->id."#".$child->route.".".$action->action}}" v-on:click="check_menu_action">
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
                    <div class="form-group col-12 text-center">
                        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                            <span v-show="button_not_loading">ارسال و ذخیره</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">وضعیت</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($roles as $role)
                <tr>
                    <td><span>{{$role->id}}</span></td>
                    <td><span>{{$role->name}}</span></td>
                    @if($role->is_active == 1)
                        <td><span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span></td>
                    @elseif($role->is_active == 0)
                        <td><span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span></td>
                    @endif
                    <td><span>{{$role->user->name}}</span></td>
                    <td><span>{{verta($role->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($role->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Roles.edit",$role->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$role->id}}" class="d-inline-block" action="{{route("Roles.destroy",$role->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('page_footer')
    <div class="form-row text-center p-3 d-flex flex-row justify-content-center">
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
