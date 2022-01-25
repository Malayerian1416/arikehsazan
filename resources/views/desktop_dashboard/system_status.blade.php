@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}" defer></script>
@endsection
@section('page_title')
    وضعیت سیستم
@endsection
@section('content')
    <form id="activation_form" action="{{route("system_status_change")}}" data-status="{{$status}}" method="post" v-on:submit="submit_activation_form">
        @csrf
        <div class="row">
            <div class="col-12">
                <h5 class="iran_yekan text-center text-muted">
                    @if($status)
                        <i class="fa fa-power-off fa-3x green_color d-block mb-2"></i>
                        وضعیت سرویس سیستم
                        <strong style="font-size: 16px;font-weight: 800;color: green">غیرفعال</strong>
                        است
                    @else
                        <i class="fa fa-plug fa-3x red_color d-block mb-2"></i>
                        وضعیت سرویس سیستم
                        <strong style="font-size: 16px;font-weight: 800;color: red">فعال</strong>
                        است
                    @endif
                </h5>
                <form action="{{route("system_status_change")}}" method="post">
                    @csrf
                    <div class="p-3 text-center w-100">
                        @if($status)
                            <button class="btn btn-outline-danger iran_yekan">
                                <i class="fa fa-plug"></i>
                                فعال کردن وضعیت سرویس
                            </button>
                        @else
                            <button class="btn btn-outline-success iran_yekan">
                                <i class="fa fa-power-off"></i>
                                غیرفعال کردن وضعیت سرویس
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
