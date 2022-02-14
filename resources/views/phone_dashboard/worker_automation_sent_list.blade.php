@extends('phone_dashboard.p_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    <span class="laleh external_page_title_text text-muted text-center"> پرداختی های کارگری تایید و ارسال شده</span>
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه و یا کارگر" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1","2"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام پروژه</th>
                <th scope="col">نام کارگر</th>
                <th scope="col">توسط</th>
                <th scope="col">مبلغ</th>
                <th scope="col">توضیحات</th>
                <th scope="col">موقعیت</th>
                <th scope="col">وضعیت</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
            </tr>
            </thead>
            <tbody>
            @forelse($worker_payments as $worker_payment)
                <tr>
                    <td><span>{{$worker_payment->id}}</span></td>
                    <td><span>{{$worker_payment->project->name}}</span></td>
                    <td><span>{{$worker_payment->contractor->name}}</span></td>
                    <td><span >{{$worker_payment->user->name}}</span></td>
                    <td><span>{{number_format($worker_payment->amount)}}</span></td>
                    <td><span>{{$worker_payment->description}}</span></td>
                    <td>
                        <span>
                            @if($worker_payment->payments)
                                تکمیل شده
                            @else
                                {{\App\Models\Role::query()->findOrFail($worker_payment->current_role_id)->name}}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>
                            @if($worker_payment->payments)
                                پرداخت شده
                            @else
                                در جریان
                            @endif
                        </span>
                    </td>
                    <td><span>{{verta($worker_payment->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($worker_payment->updated_at)->format("Y/n/d")}}</span></td>
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
