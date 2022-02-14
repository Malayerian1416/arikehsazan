@extends('phone_dashboard.p_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    <span class="laleh external_page_title_text text-muted text-center">اتوماسیون پرداختی کارگری</span>
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
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                @can('send','WorkerPayments')
                    <th scope="col">تایید و ارسال</th>
                @endcan
                @can('pay','WorkerPayments')
                    <th scope="col">تایید و پرداخت</th>
                @endcan
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
                    <td><span>{{verta($worker_payment->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($worker_payment->updated_at)->format("Y/n/d")}}</span></td>
                    @can('send','WorkerPayments')
                        <td>
                            @if($worker_payment->next_role_id != 0)
                                <form id="send_form_{{$worker_payment->id}}" class="d-inline-block" action="{{route("WorkerPayments.automate_sending",$worker_payment->id)}}" method="post" v-on:submit="submit_create_form">
                                    @csrf
                                    @method('put')
                                    <button class="index_form_submit_button" type="submit"><i class="fa fa-check-circle index_edit_icon" style="font-size: 1.8rem"></i></button>
                                </form>
                            @else
                                <i class="fa fa-times-circle index_delete_icon red_color"></i>
                            @endif
                        </td>
                    @endcan
                    @can('pay','WorkerPayments')
                        <td>
                            @if($worker_payment->next_role_id == 0)
                                <a class="index_action" role="button" href="{{route("WorkerPayments.payment",$worker_payment->id)}}">
                                    <i class="fa fa-search index_edit_icon"></i>
                                </a>
                            @else
                                <i class="fa fa-times-circle index_delete_icon red_color"></i>
                            @endif
                        </td>
                    @endcan
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
