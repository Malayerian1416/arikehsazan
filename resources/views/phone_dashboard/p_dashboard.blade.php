<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>
        {{$company_information->name}}
        -
        داشبورد
    </title>
    <link href="{{asset("/css/app.css?v=".time())}}" rel="stylesheet">
    <link href="{{asset("/css/p_dashboard.css?v=".time())}}" rel="stylesheet">
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    @yield('styles')
</head>
<body class="antialiased rtl">
<button id="select_refresher" onclick="$('.select_picker').selectpicker('refresh')" hidden></button>
<input id="csrf_token" hidden value="{{csrf_token()}}">
<div id="app" v-on:click="account_information_open">
    <div class="notification_permission_window" v-cloak v-show="notification_permission">
        <i class="fas fa-info-circle white_color fa-3x mb-2"></i>
        <h6 class="iran_yekan white_color" style="width: 200px;word-break: break-word;text-align: justify" v-text="notification_text"></h6>
        <button class="btn btn-outline-light iran_yekan mt-3" v-on:click="notification_permission = false">متوجه شدم</button>
    </div>
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-circle-notch fa-spin white_color fa-3x"></i>
    </div>
    <div class="parent">
        <div class="header_section">
            @if(Route::is("phone_idle") && !isset($menu_items))
                <div>
                    <span class="laleh white_color company_name">{{$company_information->name}}</span>
                </div>
                <div>
                    <i id="account_info_button" class="fa fa-user-circle user_icon" v-on:click="account_information_show" :class="{acc_info_active : account_info_active}"></i>
                    <div class="account_info" v-cloak v-show="account_info_active">
                        <i class="fa fa-user fa-3x w-100 p-3 text-center"></i>
                        <span class="account_info_item iran_yekan black_color border-bottom w-100 p-1">{{$user->name}}</span>
                        <span class="account_info_item iran_yekan black_color border-bottom w-100 p-1">{{$user->role->name}}</span>
                        <form action="{{route("logout")}}" method="post" class="p-3">
                            @csrf
                            <button type="button" class="account_info_item btn btn-outline-secondary">
                                <i class="fa fa-cogs fa-2x"></i>
                            </button>
                            <button type="submit" class="account_info_item btn btn-outline-secondary">
                                <i class="fa fa-sign-out-alt fa-2x"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div>
                    <a href="javascript:history.back()">
                        <button class="btn btn-outline-info">
                            <i class="fa fa-long-arrow-alt-right return_icon white_color fa-1_6x"></i>
                        </button>
                    </a>
                </div>
                <div>
                    <i class="fa fa-bars return_icon white_color ml-0" v-on:click="sidebar_toggle"></i>
                </div>
            @endif
        </div>
        <div class="content_section">
            @if(Route::is("phone_idle"))
                <div class="menu">
                    @if(!isset($menu_items))
                        @forelse($menu_headers as $menu_header)
                            @if($role->menu_items->whereIn("id",$menu_header->items->pluck("id"))->isNotEmpty())
                                <div class="w-50 d-flex flex-column justify-content-center align-items-center mb-3">
                                    <a href="{{route("phone_idle",["level" => 1,"parent_id" => $menu_header->id])}}">
                                        <button class="btn btn-light menu_button">
                                            <img class="menu_icon" alt="{{$menu_header->name}}" src="@if($menu_header->mobile_icon) {{asset("/storage/menu_header_icons/{$menu_header->id}/{$menu_header->mobile_icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                        </button>
                                    </a>
                                    <h5 class="iran_yekan mt-2">{{$menu_header->name}}</h5>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    @elseif(isset($menu_items))
                        @foreach($menu_items as $item)
                            @if($item->children->isNotEmpty())
                                @if($role->menu_items->whereIn("id",$item->children->pluck("id"))->isNotEmpty())
                                    <a class="w-100 btn btn-light mb-2" role="button" href="{{route("phone_idle",["level" => 2, "parent_id" => $item->id])}}">
                                        <div class="w-100 d-flex flex-row justify-content-start align-items-center p-1">
                                            <img class="menu_icon level1" alt="{{$item->name}}" src="@if($item->icon) {{asset("/storage/menu_item_icons/{$item->id}/{$item->icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                            <h6 class="iran_yekan mb-0 ml-3">{{$item->name}}</h6>
                                        </div>
                                    </a>
                                @endif
                            @else
                                @if(in_array($item->id,$role->menu_items->pluck("id")->toArray()) && $item->parent_id == null || in_array($item->id,$role->menu_items->pluck("id")->toArray()) && $level == 2)
                                    <a class="w-100 btn btn-light mb-2" role="button" href="{{route($role->menu_items->where("pivot.menu_item_id",$item->id)->where("pivot.menu_action_id",$item->actions->where("action",$item->main_route)->first()->id)->first()->pivot->route)}}">
                                        <div class="w-100 d-flex flex-row justify-content-start align-items-center p-1">
                                            <img class="menu_icon level1" alt="{{$item->name}}" src="@if($item->icon) {{asset("/storage/menu_item_icons/{$item->id}/{$item->icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                            <h6 class="iran_yekan mb-0 ml-3">{{$item->name}}</h6>
                                        </div>
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            @else
                <div class="external_header text-center">
                    @yield('page_title')
                </div>
                <div class="external_content border">
                    @if(session()->has("action_error"))
                        <div class="iran_yekan alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 style="font-weight: 700">
                                <i class="fa fa-times-circle" style="color: #ff0000;min-width: 30px;vertical-align: middle;text-align:center;font-size: 1.5rem"></i>
                                در هنگام انجام عملیات، خطای زیر رخ داده است :
                            </h6>
                            <ul>
                                <li>{{session("action_error")}}</li>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @yield('content')
                        @yield('page_footer')
                </div>
            @endif
        </div>
    </div>
</div>
@if(session()->has('result'))
    @if(session("result") == "saved")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات ذخیره سازی با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "updated")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات ویرایش با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "deleted")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات حذف رکورد با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "deactivated")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">رکورد مورد نظر با موفقیت غیرفعال شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "activated")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">رکورد مورد نظر با موفقیت فعال شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "sent")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات تایید و ارسال با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
    @if(session("result") == "payed")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات تایید و پرداخت با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
@endif
@yield('modal_alerts')
<script type="text/javascript" src="{{asset("/js/app.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
<script type="text/javascript" src="{{asset("/js/numeral.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/p_dashboard.js?v=".time())}}"></script>
<script src="{{asset("/js/kernel.js?v=".time())}}" defer></script>
@yield('scripts')
</body>
</html>
