@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">وضعیت سیستم</span>
@endsection
@section('content')
    <form id="activation_form" action="{{route("system_status_change")}}" data-status="{{$status}}" method="post" v-on:submit="submit_form">
        @csrf
        <div class="row">
            <div class="col-12">
                <h5 class="iran_yekan text-center text-muted">
                    @if($status)
                        <i class="fa fa-power-off fa-3x green_color d-block mb-2"></i>
                        سیستم
                        <strong style="font-size: 16px;font-weight: 800;color: green">فعال</strong>
                        است
                    @else
                        <i class="fa fa-plug fa-3x red_color d-block mb-2"></i>
                        سیستم
                        <strong style="font-size: 16px;font-weight: 800;color: red">غیر فعال</strong>
                        است
                    @endif
                </h5>
                <form action="{{route("system_status_change")}}" method="post">
                    @csrf
                    <div class="p-3 text-center w-100">
                        @if($status)
                            <button class="btn btn-outline-danger iran_yekan">
                                <i class="fa fa-plug"></i>
                                غیرفعال کردن سیستم
                            </button>
                        @else
                            <button class="btn btn-outline-success iran_yekan">
                                <i class="fa fa-power-off"></i>
                                فعال کردن سیستم
                            </button>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-12 text-center mb-2">
                <a href="{{route("link_storage")}}" class="btn btn-outline-info iran_yekan" target="_blank">اتصال رسانه ذخیره سازی</a>
            </div>
            <div class="col-12 text-center mb-2">
                <a href="{{route("serve_websocket")}}" class="btn btn-outline-info iran_yekan" target="_blank">راه اندازی وب سوکت</a>
            </div>
            <div class="col-12 text-center mb-2">
                <a href="{{route("clear_cache")}}" class="btn btn-outline-info iran_yekan" target="_blank">حذف حافظه پنهان</a>
            </div>
            <div class="col-12 text-center">
                <a href="{{route("run_schedule")}}" class="btn btn-outline-info iran_yekan" target="_blank">راه اندازی وظایف خودکار</a>
            </div>
            <div class="col-12 text-center">

            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">

    </div>
@endsection
