@extends('phone_dashboard.p_dashboard')
@section('scripts')
    <script src="{{asset("/js/jalali-moment.browser.js")}}"></script>
    @if(session()->has("print"))
        <script>
            window.open("{{route("WorkerPayments.print",session("print"))}}","_blanc");
        </script>
    @endif
    <script>
        moment.locale('fa');
        const automation_items = @json($worker_payments);
    </script>
@endsection
@section('page_title')
<span class="iran_yekan external_page_title_text text-muted text-center">اتوماسیون پرداختی کارگری</span>
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
                            <input type="search" data-table_id="inbox_table" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه و یا کارگر" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover iran_yekan index_table" id="inbox_table" data-filter='["1","2"]'>
                            <thead class="thead-bg-color">
                            <tr>
                                <th scope="col">شماره</th>
                                <th scope="col">نام پروژه</th>
                                <th scope="col">توسط</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ویرایش</th>
                                @can('send','WorkerPayments')
                                    <th scope="col">جزئیات</th>
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
                                    <td><span >{{$worker_payment->user->name}}</span></td>
                                    <td><span>{{verta($worker_payment->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($worker_payment->updated_at)->format("Y/n/d")}}</span></td>
                                    @can('details','WorkerPayments')
                                        <td>
                                            <button class="btn btn-sm btn-info iran_yekan" data-route="{{route("WorkerPayments.automate_sending",$worker_payment->id)}}" data-refer="{{route("WorkerPayments.refer",$worker_payment->id)}}" data-pay="{{route("WorkerPayments.payment",$worker_payment->id)}}" data-id="{{$worker_payment->id}}" v-on:click="worker_payment_automation_details">مشاهده</button>
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
                            <input type="search" data-table_id="outbox_table" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه و یا کارگر" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover iran_yekan index_table" id="outbox_table" data-filter='["1","2"]'>
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
                                                {{\App\Models\Role::query()->findOrFail($sent->current_role_id)->name}}
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="automation_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">جزئیات صورت وضعیت</h6>
                </div>
                <div class="modal-body" style="max-height: 70vh">
                    <div class="row no-gutters">
                        <form class="w-100" id="send_form" action="" data-type="send" method="post" v-on:submit="submit_form">
                            @csrf
                            @method('put')
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="bank_name">نام پروژه</label>
                                <input type="text" id="project_name" readonly class="form-control text-center">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="bank_name">نام کارگر</label>
                                <input type="text" id="worker_name" readonly class="form-control text-center">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="bank_name">مبلغ</label>
                                <input type="text" id="amount" name="amount" class="form-control text-center">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="bank_name">ثبت توضیحات</label>
                                <textarea id="comment" name="comment" class="form-control" v-model="worker_comment"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="project_name">توضیحات ثبت شده</label>
                                <div class="comments_container">

                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label class="col-form-label iran_yekan black_color" for="project_name">امضاء شده توسط</label>
                                <div class="sign_container">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    @can('pay','WorkerPayments')
                        <form id="pay_window_form" action="" method="get">
                            @csrf
                            <button type="submit" form="pay_window_form" class="btn btn-success">پرداخت</button>
                        </form>
                    @endcan
                    @can('send','WorkerPayments')
                        <button type="submit" form="send_form" class="btn btn-success">تایید و ارسال</button>
                    @endcan
                    @can('refer','WorkerPayments')
                        <form id="refer_form" action="" data-type="refer" method="post" v-on:submit="submit_form">
                            @csrf
                            <input type="hidden" id="refer_comment" name="refer_comment" v-model="worker_comment"/>
                            <button type="submit" form="refer_form" class="btn btn-danger">عدم تایید و ارجاع</button>
                        </form>
                    @endcan
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
