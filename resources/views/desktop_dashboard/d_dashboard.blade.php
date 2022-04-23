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
    <div class="notification_permission_window" v-cloak v-show="notification_permission">
        <i class="fas fa-info-circle white_color fa-3x mb-2"></i>
        <h6 class="iran_yekan white_color" style="width: 200px;word-break: break-word;text-align: justify" v-text="notification_text"></h6>
        <button class="btn btn-outline-light iran_yekan mt-3" v-on:click="notification_permission = false">متوجه شدم</button>
    </div>
    <div class="loading_window" v-show="loading_window_active">
        <i class="fas fa-cog fa-spin white_color"></i>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light iran_yekan" style="height: 50px">
        <a class="navbar-brand laleh" href="{{route("dashboard_home")}}">اریکه سازان</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav">
                @if(auth()->user()->is_admin)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle menu_header_text" href="#" data-toggle="dropdown"><i class="fa fa-cogs fa-1_4x pr-2" style="vertical-align: middle"></i>مدیر سامانه</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item dropdown-toggle menu_item_text" data-toggle="dropdown" href="#">مدیریت کاربران</a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item menu_item_text" href="{{route('Users.index')}}">ایجاد، مشاهده و ویرایش کاربران</a></li>
                                <li><a class="dropdown-item menu_item_text" href="{{route('Roles.index')}}">ایجاد، مشاهده و ویرایش سمت کاربران</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item dropdown-toggle menu_item_text" data-toggle="dropdown" href="#">مدیریت منو</a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item menu_item_text" href="{{route('MenuHeaders.index')}}">ایجاد، مشاهده و ویرایش گروه منو</a></li>
                                <li><a class="dropdown-item menu_item_text" href="{{route('MenuItems.index')}}">ایجاد، مشاهده و ویرایش عناوین منو</a></li>
                                <li><a class="dropdown-item menu_item_text" href="{{route('MenuActions.index')}}">ایجاد، مشاهده و ویرایش عملیات منو</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item dropdown-toggle menu_item_text" data-toggle="dropdown" href="#">مدیریت رشته و سرفصل پیمان</a>
                            <ul class="submenu dropdown-menu">
                                <li><a class="dropdown-item menu_item_text" href="{{route("ContractBranches.index")}}">ایجاد، مشاهده و ویرایش شاخه های پیمان</a></li>
                                <li><a class="dropdown-item menu_item_text" href="{{route("ContractCategories.index")}}">ایجاد، مشاهده و ویرایش سرفصل های پیمان</a></li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item menu_item_text" href="{{route("Units.index")}}">ایجاد، مشاهده و ویرایش واحد های اندازه گیری</a></li>
                        <li><a class="dropdown-item menu_item_text" href="{{route("InvoiceFlow.index")}}">ایجاد، مشاهده و ویرایش جریان صورت وضعیت</a></li>
                        <li><a class="dropdown-item menu_item_text" href="{{route("system_status_index")}}">مشاهده و تغییر وضعیت سیستم</a>
                    </ul>
                </li>
                @else
                    @forelse($menu_headers as $menu_header)
                        @if($role->menu_items->whereIn("id",$menu_header->items->pluck("id"))->isNotEmpty())
                            <li class="nav-item mr-1 dropdown">
                                <a class="nav-link menu_header_text" href="#" data-toggle="dropdown"><i class="{{$menu_header->icon->name}} fa-1_6x pr-2" style="vertical-align: middle"></i>{{$menu_header->name}}</a>
                                <ul class="dropdown-menu">
                                    @foreach($menu_header->items as $item)
                                        @if($item->children->isNotEmpty())
                                            @if($role->menu_items->whereIn("id",$item->children->pluck("id"))->isNotEmpty())
                                                <li><a class="dropdown-item dropdown-toggle menu_item_text" data-toggle="dropdown" href="#">{{$item->name}}</a>
                                                    <ul class="submenu dropdown-menu">
                                                        @foreach($item->children as $child)
                                                            @if($role->menu_items->where("id",$child->id)->isNotEmpty())
                                                                <li><a class="dropdown-item menu_item_text" href="{{route($role->menu_items->where("pivot.menu_item_id",$child->id)->where("pivot.menu_action_id",$child->actions->where("action",$child->main_route)->first()->id)->first()->pivot->route)}}">{{$child->name}}</a></li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @else
                                            @if($role->menu_items->where("id",$item->id)->isNotEmpty() && $item->parent_id == null)
                                                <li><a class="dropdown-item menu_item_text" href="{{route($role->menu_items->where("pivot.menu_item_id",$item->id)->where("pivot.menu_action_id",$item->actions->where("action",$item->main_route)->first()->id)->first()->pivot->route)}}">{{$item->name}}</a></li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @empty
                    @endforelse
                @endif
            </ul>
        </div>
        <i id="account_info_button" class="fa fa-user-circle black_color header_user_button" v-on:click="account_information_show" :class="{acc_info_active : account_info_active}"></i>
        <div class="account_info" v-cloak v-show="account_info_active">
            <i class="fa fa-user fa-3x w-100 p-3 text-center"></i>
            <span class="account_info_item iran_yekan black_color border-bottom w-100 p-1">{{$user->name}}</span>
            <span class="account_info_item iran_yekan black_color border-bottom w-100 p-1">{{$user->role->name}}</span>
            <form action="{{route("logout")}}" method="post" class="p-3">
                @csrf
                <button type="button" class="account_info_item btn btn-outline-secondary">
                    <a href="{{route("password.request")}}" style="text-decoration: none"><i class="fa fa-cogs fa-2x"></i></a>
                </button>
                <button type="submit" class="account_info_item btn btn-outline-secondary">
                    <i class="fa fa-sign-out-alt fa-2x"></i>
                </button>
            </form>
        </div>
    </nav>
    <div class="pages_container">
        @if(Route::is("idle"))
            <div class="w-100 d-flex flex-column align-items-end justify-content-center p-5" v-cloak v-show="{{"new_invoice_automation_show"}} || {{"new_worker_payment_automation_show"}}">
                <div class="card text-white bg-success mb-3" style="width: 22rem;" v-cloak v-show="{{"new_invoice_automation_show"}} || {{"new_worker_payment_automation_show"}}">
                    <div class="card-header iran_yekan">
                        <h6 class="m-0" style="font-weight: 600">
                            <i class="fa fa-bullhorn" style="vertical-align: middle;font-size: 1.2rem"></i>
                            اطلاع رسانی
                        </h6>
                    </div>
                    <div class="card-body">
                        <a class="white_color" v-show="{{"new_invoice_automation_show"}}" href="{{route("InvoiceAutomation.automation")}}">
                            <h6 class="card-title iran_yekan">
                                <span class="badge badge-pill badge-danger" style="font-size: 11px" v-cloak v-text="new_invoice_automation_text"></span>
                                <span> صورت وضعیت پیمانکاری مشاهده نشده</span>
                            </h6>
                        </a>
                        <a class="white_color" v-show="{{"new_worker_payment_automation_show"}}" href="{{route("WorkerPayments.new")}}">
                            <h6 class="card-title iran_yekan">
                                <span class="badge badge-pill badge-danger" style="font-size: 11px" v-cloak v-text="new_worker_payment_automation_text"></span>
                                <span> صورت وضعیت کارگری مشاهده نشده</span>
                            </h6>
                        </a>
                    </div>
                </div>
            </div>
            @if(auth()->user()->is_staff)
                <div class="gadget_container">
                    @yield('idle')
                </div>
            @endif
        @else
            <div class="page_content">
                <div class="w-100 bg-official page_title_container">
                <span class="iran_yekan white_color page_title">
                    @yield('page_title')
                </span>
                    <a href="{{route("idle")}}"><i class="fa fa-times-circle fa-1_4x white_color close_button"></i></a>
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
                <div class="w-100 bg-official page_footer_container d-flex justify-content-center align-items-center">
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
    @if(session("result") == "referred")
        <div class="alert_container">
            <h6 class="iran_yekan">
                <i class="fa fa-check-circle d-block fa-4x pb-2" style="color: #69fc3d;vertical-align: middle;text-align:center"></i>
                <span class="white_color">عملیات ارجاع با موفقیت انجام شد</span>
            </h6>
        </div>
    @endif
@endif
<script src="{{asset("js/app.js?v=".time())}}"></script>
<script src="{{asset("/js/numeral.js")}}"></script>
<script src="{{asset("/js/persianDatepicker.min.js")}}"></script>
<script src="{{asset("/js/d_dashboard.js?v=".time())}}"></script>
<script src="{{asset("/js/kernel.js?v=".time())}}" defer></script>
@yield('scripts')
<div id="menu_search" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input id="menu_items_search_key" list="menu_items_datalist" class="form-control text-center iran_yekan" type="search" placeholder="جستجو در گزینه های منو">
                <datalist id="menu_items_datalist">
                    <option>مدیریت پروژه</option>
                    <option>مدیریت پیمان</option>
                    <option>مدیریت پیمانکار</option>
                    <option>مدیریت وضعیت</option>
                </datalist>
            </div>
        </div>
    </div>
</div>
</body>
</html>
