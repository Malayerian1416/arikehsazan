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
<button id="select_refresher" onclick="$('.select_picker').selectpicker('refresh')" hidden>
</button>
<div id="app" v-on:click="account_information_open">
    <div class="notification_permission_window" v-cloak v-show="notification_permission">
        <i class="fas fa-info-circle white_color fa-3x mb-2"></i>
        <h6 class="iran_yekan white_color" style="width: 200px;word-break: break-word;text-align: justify" v-text="notification_text"></h6>
        <button class="btn btn-outline-light iran_yekan mt-3" v-on:click="notification_permission = false">متوجه شدم</button>
    </div>
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-circle-notch fa-spin white_color fa-3x"></i>
    </div>
    <div class="header_section">
        @if(Route::is("phone_idle"))
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
                <a style="text-decoration: none" href="{{route("phone_idle")}}">
                    <button class="btn btn-outline-info">
                        <i class="fa fa-arrow-right return_icon white_color"></i>
                        <span class="iran_yekan white_color">صفحه اصلی</span>
                    </button>
                </a>
            </div>
            <div>
                <i class="fa fa-bars return_icon white_color ml-0" v-on:click="sidebar_toggle"></i>
            </div>
        @endif
    </div>
    <div class="content_section @if(Route::is("phone_idle") && count($user_menu->toArray()) <= 1) no_footer @elseif(!Route::is("phone_idle")) low_footer @endif">
        @if(Route::is("phone_idle"))
            <div class="menu">
                @forelse($user_menu as $menu_header)
                    <div id="{{$menu_header->slug}}" class="menu_header @if($loop->first) show @endif">
                        @forelse($menu_header->menu_titles as $menu_title)
                            <div class="mb-2">
                                <h6 class="iran_yekan text-muted" style="font-size: 1rem">{{$menu_title->name}}</h6>
                            </div>
                            <div class="menu_row">
                                @forelse($menu_title->menu_items as $menu_item)
                                    <a class="menu_link" href="{{route($menu_title->route.".".$menu_item->main_route)}}">
                                        <div class="menu_col" style="width: 100%">
                                            <div class="menu_item">
                                                @if($menu_item->icon != "" && $menu_item->icon != null)
                                                    <div style="height: 60%;display: flex;align-items: center;justify-content: center">
                                                        <img alt="img" class="menu_item_icon" src="{{asset("/storage/menu_item_icons/{$menu_item->id}/{$menu_item->icon}")}}"/>
                                                    </div>
                                                @endif
                                                @if($menu_item->short_name != "" && $menu_item->short_name != null)
                                                    <div style="height: 40%">
                                                        <span class="iran_yekan menu_item_title text-center">{{$menu_item->short_name}}</span>
                                                    </div>
                                                @endif
                                                @if($menu_item->notifiable)
                                                    <span class="iran_yekan badge badge-pill badge-danger" style="font-size: 12px" v-cloak v-text="{{$menu_item->notification_channel."_text"}}" v-show="{{$menu_item->notification_channel."_show"}}"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                @endforelse
                            </div>
                        @empty
                        @endforelse
                    </div>
                @empty
                @endforelse
            </div>
        @else
            <div class="external_header text-center">
                @yield('page_title')
            </div>
            <div class="external_content border">
                <div class="page_content_body">
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
                </div>
                @yield('content')
            </div>
        @endif
    </div>
    @if(Route::is("phone_idle"))
        @if(count($user_menu->toArray()) > 1)
            <div class="footer_section">
                @forelse($user_menu as $menu_header)
                    <div id="{{$menu_header->slug}}_header" class="menu_header_container @if(Route::is("phone_idle") && $loop->first || Route::is($menu_header->menu_titles->pluck("main_route")->toArray())) active @else {{null}} @endif">
                        <div class="menu_header_footer">
                            <i class="{{$menu_header->icon->name}} menu_header_icon"></i>
                            <span class="iran_yekan menu_header_title">{{$menu_header->name}}</span>
                        </div>
                        <div data-slug="{{$menu_header->slug}}" style="position: absolute;width: 100%;height: 100%;top: 0;left: 0" v-on:click="phone_menu_header"></div>
                    </div>
                @empty
                @endforelse
            </div>
        @endif
    @else
        <div class="page_buttons">
            @yield('page_footer')
        </div>
    @endif
    <div class="sidebar_section sidenav_body @if(Route::is("phone_idle") && count($user_menu->toArray()) <= 1) no_footer @elseif(!Route::is("phone_idle")) low_footer @endif" v-cloak v-show="sidebar_visibility">
        @forelse($user_menu as $menu_header)
            <li class="nav-item" role="presentation">
                <a class="nav-link menu_tab_link @if(Route::is("idle") && $loop->first) active @elseif(Route::is($menu_header->menu_titles->pluck("main_route")->toArray())) active @else {{null}} @endif" id="{{$menu_header->slug}}-tab" data-toggle="tab" href="#{{$menu_header->slug}}" role="tab" aria-controls="{{$menu_header->slug}}" aria-selected="true">
                    <i class="{{$menu_header->icon->name}} menu_header_icon"></i>
                </a>
            </li>
        @empty
        @endforelse
            <div class="tab-content" id="myTabContent">
                @forelse($user_menu as $menu_header)
                    <div class="tab-pane fade @if(Route::is("idle") && $loop->first) show active @elseif(Route::is($menu_header->menu_titles->pluck("main_route")->toArray())) show active @else {{null}} @endif" id="{{$menu_header->slug}}" role="tabpanel" aria-labelledby="{{$menu_header->slug}}-tab">
                        @forelse($menu_header->menu_titles as $menu_title)
                            <button class="dropdown-btn iran_yekan {{(Route::is($menu_title->main_route)) ? 'menu_dropdown_active':null}}">
                                       <span>
                                            @if($menu_title->icon != null)
                                               <i class="menu_title_icon {{$menu_title->icon}}"></i>
                                           @endif
                                           {{$menu_title->name}}
                                       </span>
                                <i class="fa fa-caret-down" style="vertical-align: middle"></i>
                            </button>
                            <div class="dropdown-container {{(Route::is($menu_title->main_route)) ? 'active':null}}">
                                @forelse($menu_title->menu_items as $menu_item)
                                    <a class="iran_yekan {{(Route::is($menu_item->actions->pluck('action')->toArray())) ? 'dropdown-item_active':null}}" href="{{route($menu_title->route.".".$menu_item->main_route)}}">{{$menu_item->name}}
                                        @if($menu_item->notifiable)
                                            <span class="badge badge-pill badge-danger" style="font-size: 12px" v-cloak v-text="{{$menu_item->notification_channel."_text"}}" v-show="{{$menu_item->notification_channel."_show"}}"></span>
                                        @endif
                                    </a>
                                @empty
                                @endforelse
                            </div>
                        @empty
                        @endforelse
                    </div>
                @empty
                @endforelse
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
</div>
<script type="text/javascript" src="{{asset("/js/app.js?v=".time())}}"></script>
<script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
<script type="text/javascript" src="{{asset("/js/numeral.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
<script type="text/javascript" src="{{asset("/js/p_dashboard.js?v=".time())}}"></script>
<script src="{{asset("/js/kernel.js?v=".time())}}" defer></script>
@yield('scripts')
</body>
</html>
