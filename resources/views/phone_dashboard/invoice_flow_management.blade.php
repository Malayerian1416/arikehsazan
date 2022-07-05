@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">مدیریت جریان صورت وضعیت</span>
@endsection
@section('content')
    <div class="flow_container">
        <h5 class="iran_yekan border-bottom p-3">ثبت کننده(ها)</h5>
        <div class="flow_container_starter">
            @forelse($invoice_flow->where('is_starter',1) as $starter)
                <div class="rectangle starter">
                    <span class="iran_yekan">{{$starter->priority." - ".$starter->role->name}}</span>
                </div>
                @if(!$loop->last)
                    <img src="{{asset("/img/comma.svg")}}" class="comma" alt="comma">
                @endif
            @empty
                <span class="iran_yekan">اطلاعاتی ثبت نشده است</span>
            @endforelse
        </div>
        <img src="{{asset("/img/arrow-down.svg")}}" class="arrow" alt="arrow">
        <h5 class="iran_yekan border-bottom p-3">واسطه(ها)</h5>
        <div class="flow_container_inductor">
            @forelse($invoice_flow->where('is_starter',0)->where('is_finisher',0) as $inductor)
                <div class="rectangle inductor">
                    <span class="iran_yekan">{{$inductor->priority." - ".$inductor->role->name}}</span>
                </div>
                @if(!$loop->last)
                    <img src="{{asset("/img/arrow-left.svg")}}" class="arrow" alt="arrow">
                @endif
            @empty
                <span class="iran_yekan">اطلاعاتی ثبت نشده است</span>
            @endforelse
        </div>
        <img src="{{asset("/img/arrow-down.svg")}}" class="arrow" alt="arrow">
        <h5 class="iran_yekan border-bottom p-3">خاتمه دهنده</h5>
        <div class="flow_container_finisher">
            @forelse($invoice_flow->where('is_finisher',1) as $finisher)
                <div class="rectangle finisher">
                    <span class="iran_yekan">{{$finisher->priority." - ".$finisher->role->name}}</span>
                </div>
                @if(!$loop->last)
                    <img src="{{asset("/img/arrow-left.svg")}}" class="arrow" alt="arrow">
                @endif
            @empty
                <span class="iran_yekan">اطلاعاتی ثبت نشده است</span>
            @endforelse
        </div>
    </div>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <a href="{{route("InvoiceFlow.create")}}">
            <button form="create_form" class="btn btn-outline-primary iran_yekan mr-2">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                <span v-show="button_not_loading">ایجاد و ویرایش جریان</span>
            </button>
        </a>
        <a href="{{route("InvoiceFlow.permissions")}}">
            <button form="create_form" class="btn btn-outline-info iran_yekan mr-2">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-layer-group button_icon"></i>
                <span v-show="button_not_loading">تعیین مجوزهای ویرایش اتوماسیون وضعیت</span>
            </button>
        </a>

    </div>
@endsection
