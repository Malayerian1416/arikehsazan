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
    <link href="{{asset("/css/d_dashboard.css?v=".time())}}" rel="stylesheet">
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    @yield('styles')
</head>
<body class="antialiased rtl">
<button id="select_refresher" onclick="$('.select_picker').selectpicker('refresh')" hidden>
</button>
<div id="app" v-on:click="account_information_open">
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-cog fa-spin white_color fa-3x"></i>
        <span class="iran_yekan white_color mt-2">لطفا منتظر بمانید</span>
    </div>
    <div class="header_container">
        <i class="fa fa-bars header_menu_button"></i>
        <div>
            <i class="fa fa-gear fa-2x black_color pr-3 header_button"></i>
            <i id="account_info_button" class="fa fa-user-circle black_color header_user_button" v-on:click="account_information_show" :class="{acc_info_active : account_info_active}"></i>
            <div class="account_info" v-show="account_info_active">
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
    <div class="dashboard_container p-0 m-0 rtl">
        <aside class="sidenav bg-dark">
            <div class="sidenav_title bg-dark" style="border-bottom: 1px solid rgba(77,77,77,0.67)">
                <h6 class="laleh text-center m-0 p-0 company_name">
                    {{$company_information->name}}
                </h6>
            </div>
            <div class="sidenav_body">
                <ul class="nav nav-tabs iran_yekan menu_tab" id="myTab" role="tablist">
                    @if(auth()->user()->is_admin)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link menu_tab_link active" id="admin-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="true">
                                <i class="fa fa-user-cog menu_header_icon"></i>
                            </a>
                        </li>
                    @elseif(auth()->user()->is_staff)
                        @forelse($user_menu as $menu_header)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link menu_tab_link @if(Route::is("idle") && $loop->first) active @elseif(Route::is($menu_header->menu_titles->pluck("main_route")->toArray())) active @else {{null}} @endif" id="{{$menu_header->slug}}-tab" data-toggle="tab" href="#{{$menu_header->slug}}" role="tab" aria-controls="{{$menu_header->slug}}" aria-selected="true">
                                    <i class="{{$menu_header->icon->name}} menu_header_icon"></i>
                                </a>
                            </li>
                        @empty
                        @endforelse
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if(auth()->user()->is_admin)
                        <div class="tab-pane fade show active" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                            <button class="dropdown-btn iran_yekan @if(Route::is(['MenuHeaders.*','MenuTitles.*','MenuItems.*','MenuActions.*'])) menu_dropdown_active @endif">
                                مدیریت منو
                                <i class="fa fa-caret-down" style="vertical-align: middle"></i>
                            </button>
                            <div class="dropdown-container @if(Route::is(['MenuHeaders.*','MenuTitles.*','MenuItems.*','MenuActions.*'])) active @endif">
                                <a class="iran_yekan @if(Route::is('MenuHeaders.*')) dropdown-item_active @endif" href="{{route('MenuHeaders.index')}}">ایجاد، مشاهده و ویرایش عناوین گروه منو</a>
                                <a class="iran_yekan @if(Route::is('MenuTitles.*')) dropdown-item_active @endif" href="{{route('MenuTitles.index')}}">ایجاد، مشاهده و ویرایش عناوین اصلی منو</a>
                                <a class="iran_yekan @if(Route::is('MenuItems.*')) dropdown-item_active @endif" href="{{route('MenuItems.index')}}">ایجاد، مشاهده و ویرایش عناوین فرعی منو</a>
                                <a class="iran_yekan @if(Route::is('MenuActions.*')) dropdown-item_active @endif" href="{{route('MenuActions.index')}}">ایجاد، مشاهده و ویرایش عملیات وابسته منو</a>
                            </div>
                            <button class="dropdown-btn iran_yekan @if(Route::is("Roles.create")||Route::is("Roles.index")||Route::is("Roles.edit")) menu_dropdown_active @endif}}">
                                مدیریت سمت کاربران
                                <i class="fa fa-caret-down" style="vertical-align: middle"></i>
                            </button>
                            <div class="dropdown-container @if(Route::is("Roles.create")||Route::is("Roles.index")||Route::is("Roles.edit")) active @endif">
                                <a class="iran_yekan @if(Route::is("Roles.create")) dropdown-item_active @endif" href="{{route("Roles.create")}}">ایجاد سمت جدید</a>
                                <a class="iran_yekan @if(Route::is("Roles.index")||Route::is("Roles.edit")) dropdown-item_active @endif" href="{{route("Roles.index")}}">مشاهده و ویرایش سمت ها</a>
                            </div>
                            <button class="dropdown-btn iran_yekan @if(Route::is("Users.create")||Route::is("Users.index")||Route::is("Users.edit")) menu_dropdown_active @endif}}">
                                مدیریت کاربران سامانه
                                <i class="fa fa-caret-down" style="vertical-align: middle"></i>
                            </button>
                            <div class="dropdown-container @if(Route::is("Users.create")||Route::is("Users.index")||Route::is("Users.edit")) active @endif">
                                <a class="iran_yekan @if(Route::is("Users.create")) dropdown-item_active @endif" href="{{route("Users.create")}}">ایجاد کاربر جدید</a>
                                <a class="iran_yekan @if(Route::is("Users.index")||Route::is("Users.edit")) dropdown-item_active @endif" href="{{route("Users.index")}}">مشاهده و ویرایش کاربران</a>
                            </div>
                            <button class="dropdown-btn iran_yekan @if(Route::is("ContractBranches.*")||Route::is("ContractCategories.*")) menu_dropdown_active @endif}}">
                                مدیریت رشته و سرفصل پیمان
                                <i class="fa fa-caret-down" style="vertical-align: middle"></i>
                            </button>
                            <div class="dropdown-container @if(Route::is("ContractBranches.*")||Route::is("ContractCategories.*")) active @endif">
                                <a class="iran_yekan @if(Route::is("ContractBranches.*")) dropdown-item_active @endif" href="{{route("ContractBranches.index")}}">ایجاد، مشاهده و ویرایش شاخه های پیمان</a>
                                <a class="iran_yekan @if(Route::is("ContractCategories.*")) dropdown-item_active @endif" href="{{route("ContractCategories.index")}}">ایجاد، مشاهده و ویرایش سرفصل های پیمان</a>
                            </div>
                            <a class="iran_yekan @if(Route::is("Units.*")) dropdown-item_active @endif" href="{{route("Units.index")}}">ایجاد، مشاهده و ویرایش واحد های اندازه گیری</a>
                            <a class="iran_yekan @if(Route::is("InvoiceFlow.*")) dropdown-item_active @endif" href="{{route("InvoiceFlow.index")}}">ایجاد، مشاهده و ویرایش جریان صورت وضعیت</a>
                            <a class="iran_yekan @if(Route::is("system_status_index")) dropdown-item_active @endif" href="{{route("system_status_index")}}">مشاهده و تغییر وضعیت سیستم</a>
                        </div>
                    @elseif(auth()->user()->is_staff)
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
                    @endif
                </div>
            </div>
            <div class="sidenav_footer bg-dark">
                <span class="iran_yekan pt-2 d-block text-muted-light text-center" style="font-size: 10px">
                        نسخه سیستمی
                        ({{env("APP_VERSION")}})
                    </span>
            </div>
        </aside>
    </div>
    <div class="pages_container">
        @if(Route::is("idle"))
            <div class="gadget_container">
                <div class="gadget d-flex flex-row align-items-center justify-content-start" style="width: 33%;">
                    <h6 class="p-3 text-center h-100 bg-dark" style="margin:0;width: 35px;writing-mode: vertical-lr;background: #ffffff;border-top-right-radius: 5px;border-bottom-right-radius: 5px">
                        <span class="iran_yekan white_color" style="font-size: 1.3rem">اطلاعات پروژه ها</span>
                    </h6>
                    <div class="owl-carousel owl-theme" style="width: calc(100% - 35px);height: 100%">
                        @forelse($projects as $project)
                            <div class="item w-100 p-3 h-100">
                                @php
                                    $start = explode("/",$project->project_start_date);
                                    $end = explode("/",$project->project_completion_date);
                                    $start = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($start[0],$start[1],$start[2])));
                                    $end = verta(implode("-",\Hekmatinasser\Verta\Verta::getGregorian($end[0],$end[1],$end[2])));
                                    $total_diff = $start->diffDays($end);
                                    $percent_diff = $start->diffDays();
                                    $remain = $total_diff - $percent_diff >= 1 ? $total_diff - $percent_diff : 0;
                                    $percent = ceil(($percent_diff / $total_diff) * 100) <= 100 ? ceil(($percent_diff / $total_diff) * 100) : 100;
                                    $color = ceil(($percent / 100) * 255);
                                    $invoice_sum = 0;
                                    $worker_sum = 0;
                                @endphp
                                <div class="gadget_title">
                                    <h6 class="iran_yekan mb-4" style="font-size: 1.5rem">{{$project->name}}</h6>
                                </div>
                                <div class="mb-4">
                                    <h6 class="iran_yekan mb-3 white_color text-muted-light">مدت زمان سپری شده</h6>
                                    <div class="w-100 border mb-3 d-flex justify-content-center align-items-center progress_bar_container">
                                        <div class="progress_bar" style="background: rgb({{$color}},{{255-$color}},0);width: {{$percent}}%">
                                        </div>
                                        <span class="iran_yekan" style="z-index: 100">{{$percent."%"}}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="iran_yekan mb-3 white_color text-muted-light">کل هزینه های وضعیتی</h6>
                                        <div class="mb-3">
                                            <h6 class="iran_yekan" style="font-size: 1.5rem">
                                                @forelse($project->contracts as $contract)
                                                    @forelse($contract->invoices as $invoice)
                                                        @php
                                                            $invoice_sum += array_sum($invoice->payments->pluck("amount_payed")->toArray())
                                                        @endphp
                                                    @empty
                                                    @endforelse
                                                @empty
                                                @endforelse
                                                {{number_format($invoice_sum)." ریال"}}
                                            </h6>
                                        </div>
                                        <h6 class="iran_yekan mb-3 white_color text-muted-light">کل هزینه های کارگری</h6>
                                        <div>
                                            <h6 class="iran_yekan" style="font-size: 1.5rem">
                                                @forelse($project->worker_automations as $worker_automation)
                                                    @if($worker_automation->payments != null)
                                                        @php
                                                            $worker_sum += $worker_automation->payments->amount_payed;
                                                        @endphp
                                                    @endif
                                                @empty
                                                @endforelse
                                                {{number_format($worker_sum)." ریال"}}
                                            </h6>
                                        </div>
                                    </div>
                                    <div>
                                        <table class="w-100 iran_yekan table table-bordered text-center project_gadget_table">
                                            <tr>
                                                <td>{{$project->project_start_date}}</td>
                                                <td>کل روزها</td>
                                                <td>{{"$total_diff روز"}}</td>
                                            </tr>
                                            <tr>
                                                <td>لغایت</td>
                                                <td>سپری شده</td>
                                                <td>{{"$percent_diff روز"}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$project->project_completion_date}}</td>
                                                <td>باقیمانده</td>
                                                <td>{{"$remain روز"}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h6 class="iran_yekan" style="font-size: 1.5rem">پروژه ای یافت نشد!</h6>
                        @endforelse
                    </div>
                </div>
                @else
                    <div class="page_content">
                        <div class="w-100 bg-dark page_title_container">
                <span class="iran_yekan white_color h-100 pl-3 m-0 page_title">
                    @yield('page_title')
                </span>
                        </div>
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
                            @yield('content')
                        </div>
                        <div class="w-100 bg-dark page_footer_container d-flex justify-content-center align-items-center">
                            @yield('page_footer')
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
    <script src="{{asset("js/app.js?v=".time())}}"></script>
    <script src="{{asset("/js/jquery.mask.js")}}"></script>
    <script src="{{asset("/js/numeral.js")}}"></script>
    <script src="{{asset("/js/persianDatepicker.min.js")}}"></script>
    <script src="{{asset("/js/d_dashboard.js?v=".time())}}"></script>
    <script src="{{asset("/js/kernel.js?v=".time())}}" defer></script>
@yield('scripts')
</body>
</html>
