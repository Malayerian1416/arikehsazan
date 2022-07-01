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
    @laravelPWA
    <link href="{{asset("/css/app.css?v=".$company_information->app_ver)}}" rel="stylesheet">
    <link href="{{asset("/css/p_dashboard.css?v=".$company_information->app_ver.time())}}" rel="stylesheet">
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    @yield('styles')
</head>
<style>
    @if(isset($menu_items) || !Route::is("idle"))
        .menu_parent {
        padding-top: 75px;
    }
    .header_section{
        min-height: 60px;
        background:#FFFFFF;
    }
    @endif
</style>
<body class="antialiased rtl">
<button id="select_refresher" onclick="$('.select_picker').selectpicker('refresh')" hidden></button>
<input id="csrf_token" hidden value="{{csrf_token()}}">
<div id="app" v-on:click="account_information_open">
    <div class="install-app-btn-container">
        <div class="p-4 d-flex flex-column justify-content-center align-items-center rounded">
            <span class="iran_yekan">آیا مایلید برنامه بر روی تلفن همراه شما نصب گردد؟</span>
            <div class="pt-3">
                <button id="installApp" class="btn btn-primary iran_yekan mr-4">بلی</button>
                <button id="denyApp" class="btn btn-danger iran_yekan">خیر</button>
            </div>
        </div>
    </div>
    <div class="loading_window" v-show="loading_window_active">
        <div class="bg-light p-4 d-flex flex-row justify-content-between align-items-center rounded" style="width: 70vw">
            <span class="iran_yekan">در حال پردازش...</span>
            <i class="fa fa-circle-notch fa-spin fa-2x"></i>
        </div>
    </div>
    <div class="header_section position-fixed">
        @if(Route::is("idle") && !isset($menu_items))
            <div class="header_information w-100 d-flex flex-row align-items-center justify-content-between mb-4">
                <div>
                    <span class="iran_yekan company_name white_color">{{$company_information->name}}</span>
                </div>
                <div>
                    <i id="account_info_button" class="fa fa-ellipsis-v user_icon" v-on:click="account_information_show" :class="{acc_info_active : account_info_active}"></i>
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
            </div>
            <div class="user_information_box_container">
                <div class="user_information_box w-100 h-100 d-flex flex-row align-items-center justify-content-around">
                    <div class="user_icon_container text-center">
                        <img class="user_information_icon" @if($user->gender == "مرد") src="{{asset("/img/men-avatar.png")}}" @elseif($user->gender == "زن") src="{{asset("/img/women-avatar.png")}}" @else src="{{asset("/img/user_information.png")}}" @endif alt="user_png">
                    </div>
                    <div class="user_information_container d-flex flex-column">
                        <h5 class="user_name iran_yekan m-0">
                            {{$user->name}}
                        </h5>
                        <span class="user_role iran_sans text-muted mb-2">{{$user->role->name}}</span>
                        <div>
                            <h6 class="iran_yekan m-0" style="font-weight: bold">0 روز</h6>
                            <span class="iran_sans text-muted" style="font-weight: normal">مرخصی استفاده شده</span>
                        </div>
                        <progress id="progress" value="0" max="36"></progress>
                    </div>
                </div>
            </div>
        @else
            <div class="back_section w-100 h-100">
                <form action="{{route("back")}}" method="post" v-on:submit="loading_window_active = true">
                    @csrf
                    <button type="submit" class="invisible_button iran_yekan m-0 p-0">
                        <i class="fa fa-arrow-right fa-1_4x mr-3 align_middle"></i>
                        @if(Route::is("idle") && isset($level) && $level == 1)
                            صفحه اصلی
                        @elseif(Route::is("idle") && isset($level) && $level == 2)
                            {{$menu_name}}
                        @elseif(!Route::is("idle"))
                            @yield('page_title')
                        @endif
                    </button>
                </form>
            </div>
        @endif
    </div>
    <div class="page_content">
        @if(Route::is("idle"))
            <div class="menu_parent">
                <div class="menu_section">
                    <h5 class="menu_section_title iran_yekan pb-2 mb-4 border-bottom">
                        <i class="fa fa-th-list fa-1_2x mr-1"></i>
                        @if(!isset($menu_items))
                            منو
                        @else
                            {{$menu_name}}
                        @endif
                    </h5>
                    <div class="menu_items_container">
                        @if(!isset($menu_items))
                            @forelse($menu_headers as $menu_header)
                                @if($role->menu_items->whereIn("id",$menu_header->items->pluck("id"))->isNotEmpty())
                                    <div class="text-center" style="display: grid;grid-template-columns: 1fr">
                                        <a href="{{route("idle",["level" => 1,"parent_id" => $menu_header->id])}}">
                                            <button class="btn btn-outline-light menu_button">
                                                <img class="menu_icon" alt="{{$menu_header->name}}" src="@if($menu_header->mobile_icon) {{asset("/storage/menu_header_icons/{$menu_header->id}/{$menu_header->mobile_icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                            </button>
                                        </a>
                                        <h6 class="iran_sans m-0 mt-2 text-center menu_item_text">{{$menu_header->name}}</h6>
                                    </div>
                                @endif
                            @empty
                            @endforelse
                        @else
                            @foreach($menu_items as $item)
                                @if($item->children->isNotEmpty())
                                    @if($role->menu_items->whereIn("id",$item->children->pluck("id"))->isNotEmpty())
                                        <div class="text-center" style="display: grid;grid-template-columns: 1fr">
                                            <a href="{{route("idle",["level" => 2, "parent_id" => $item->id])}}">
                                                <button class="btn btn-outline-light menu_button">
                                                    <img class="menu_icon" alt="{{$item->name}}" src="@if($item->icon) {{asset("/storage/menu_item_icons/{$item->id}/{$item->icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                                </button>
                                            </a>
                                            <h6 class="iran_sans m-0 mt-2 text-center menu_item_text">{{$item->short_name}}</h6>
                                        </div>
                                    @endif
                                @else
                                    @if(in_array($item->id,$role->menu_items->pluck("id")->toArray()) && $item->parent_id == null || in_array($item->id,$role->menu_items->pluck("id")->toArray()) && $level == 2)
                                        <div class="text-center" style="display: grid;grid-template-columns: 1fr">
                                            <a href="{{route($role->menu_items->where("pivot.menu_item_id",$item->id)->where("pivot.menu_action_id",$item->actions->where("action",$item->main_route)->first()->id)->first()->pivot->route)}}">
                                                <button class="btn btn-outline-light menu_button">
                                                    <img class="menu_icon level1" alt="{{$item->name}}" src="@if($item->icon) {{asset("/storage/menu_item_icons/{$item->id}/{$item->icon}")}} @else {{asset("/img/no_image.png")}} @endif">
                                                </button>
                                            </a>
                                            <h6 class="iran_sans m-0 mt-2 text-center menu_item_text">{{$item->short_name}}</h6>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @if(Route::is("idle") && !isset($menu_items))
                    <div class="menu_section" v-cloak v-show="new_invoice_automation_show || new_worker_payment_automation_show">
                        <h5 class="menu_section_title iran_yekan pb-2 mb-4 border-bottom">
                            <i class="fa fa-bullhorn mr-1 fa-1_2x"></i>
                            اطلاع رسانی
                        </h5>
                        <div class="notification_container">
                            <div v-show="new_invoice_automation_show" class="notification_badge">
                                <a href="{{route("InvoiceAutomation.automation")}}">
                                    <div>
                                        <i class="fa fa-file-invoice fa-1_4x mr-2"></i>
                                        <span class="iran_sans bold_font">وضعیت پیمانکاری جدید</span>
                                    </div>
                                    <span class="badge badge-danger badge-pill iran_sans bold_font" style="font-size: 12px" v-text="new_invoice_automation_text"></span>
                                </a>
                            </div>
                            <div v-show="new_worker_payment_automation_show" class="notification_badge">
                                <a href="{{route("WorkerPayments.automation")}}">
                                    <div>
                                        <i class="fa fa-clipboard fa-1_4x mr-2"></i>
                                        <span class="iran_sans bold_font">وضعیت پرداختی کارگری جدید</span>
                                    </div>
                                    <span class="badge badge-danger badge-pill iran_sans bold_font" style="font-size: 12px" v-text="new_worker_payment_automation_text"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="content_section">
                <div class="content_data">
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
                </div>
            </div>
        @endif
    </div>
    @yield('modal_alerts')
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
<script>
    const role_id = {{$user->role->id}};
    @if($invoice_count > 0)
    const new_invoice_automation_show_already = true;
    const new_invoice_automation_text_already = {{$invoice_count}};
    @endif
    @if($worker_count > 0)
    const new_worker_automation_show_already = true;
    const new_worker_automation_text_already = {{$worker_count}};
    @endif
</script>
<script src="{{asset("/js/app.js?v=".$company_information->app_ver)}}"></script>
<script src="{{asset("/js/jquery.mask.js")}}" defer></script>
<script src="{{asset("/js/numeral.js")}}"></script>
<script src="{{asset("/js/persianDatepicker.min.js")}}"></script>
<script src="{{asset("/js/p_dashboard.js?v=".$company_information->app_ver)}}"></script>
<script src="{{asset("/js/kernel.js?v=".$company_information->app_ver)}}"></script>
@auth
    <script src="{{asset("/js/enable_push.js?v=".$company_information->app_ver)}}"></script>
@endauth
@yield('scripts')
</body>
</html>
