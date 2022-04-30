@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
    @if(session()->has("print"))
        <script>
            window.open("{{route("WorkerPayments.print",session("print"))}}","_blanc");
        </script>
    @endif
@endsection
@section('page_title')
    اتوماسیون پرداختی کارگری
@endsection
@section('content')
    <div class="card h-100" style="overflow: hidden;max-height: 100%">
        <div class="card-header iran_yekan">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox" aria-selected="true">صندوق ورودی</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="outbox-tab" data-toggle="tab" href="#outbox" role="tab" aria-controls="outbox" aria-selected="false">صندوق خروجی</a>
                </li>
            </ul>
        </div>
        <div class="card-body" style="overflow: hidden">
            <div class="tab-content" id="myTabContent" style="overflow: hidden">
                <div class="tab-pane fade show active" id="inbox" role="tabpanel" aria-labelledby="inbox-tab" style="overflow: hidden;height:calc(100vh - 200px)">
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
                </div>
                <div class="tab-pane fade" id="outbox" role="tabpanel" aria-labelledby="outbox-tab" style="overflow: hidden;height:calc(100vh - 200px)">
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
                                <th scope="col">وضعیت</th>
                                <th scope="col">پرداختی</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ویرایش</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sent_worker_payments as $sent)
                                <tr>
                                    <td><span>{{$sent->id}}</span></td>
                                    <td><span>{{$sent->project->name}}</span></td>
                                    <td><span>{{$sent->contractor->name}}</span></td>
                                    <td><span >{{$sent->user->name}}</span></td>
                                    <td><span>{{number_format($sent->amount)}}</span></td>
                                    <td><span>{{$sent->description}}</span></td>
                                    <td>
                                        <span>
                                            @if($sent->current_role_id <> 0)
                                                {{\App\Models\Role::query()->findOrFail($invoice_automation->current_role_id)->name}}
                                            @else
                                                تکمیل شده
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            @if($sent->payments)
                                                پرداخت شده
                                            @else
                                                منتظر پرداخت
                                            @endif
                                        </span>
                                    </td>
                                    <td><span>{{verta($sent->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($sent->updated_at)->format("Y/n/d")}}</span></td>
                                    <td><a class="print_anchor" href="{{route("WorkerPayments.print",$sent->id)}}" target="_blank"><i class="fa fa-print fa-1_4x"></i></a></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
