@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    تعیین مجوزهای ویرایش مقادیر صورت وضعیت
@endsection
@section('content')
    <form id="create_form" action="{{route("InvoiceFlow.set_permissions")}}" method="post" data-type="create" v-on:submit="submit_form">
        @csrf
        <div class="form-row">
            @forelse($invoice_flow as $flow)
                <div class="form-group col-md-12 col-lg-3">
                    <h5 class="iran_yekan">{{$flow->role->name}}</h5>
                    <select class="form-control iran_yekan select_picker" title="انتخاب کنید" data-actions-box="true" multiple name="{{"record@".$flow->id}}[]">
                        <option value="quantity" @if($flow->quantity == 1) selected @endif>کارکرد</option>
                        <option value="amount" @if($flow->amount == 1) selected @endif>بهاء جزء</option>
                        <option value="payment_offer" @if($flow->payment_offer == 1) selected @endif>پیشنهاد پرداخت</option>
                    </select>
                </div>
            @empty
            @endforelse
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ذخیره</span>
        </button>
        <a href="{{route("InvoiceFlow.index")}}">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-arrow-circle-right button_icon"></i>
                <span v-show="button_not_loading">بازگشت به لیست</span>
            </button>
        </a>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
