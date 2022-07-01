@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    صورت وضعیت های جدید
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
                            <input type="search" data-table_id="inbox_table" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه، پیمان و پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover iran_yekan index_table main_table" id="inbox_table" data-filter='[1,2,3]'>
                            <thead class="thead-bg-color">
                            <tr>
                                <th scope="col">شماره</th>
                                <th scope="col">پروژه</th>
                                <th scope="col">پیمان</th>
                                <th scope="col">پیمانکار</th>
                                <th scope="col">نام ایجاد کننده</th>
                                <th scope="col">سمت ایجاد کننده</th>
                                <th scope="col"> شماره وضعیت</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ارسال</th>
                                <th scope="col">جزئیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoice_automations_inbox as $invoice_automation)
                                <tr class="@if($invoice_automation->is_read == 0) bold_font black_color @else text-muted @endif">
                                    <td><span>{{$invoice_automation->id}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->contract->project->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->contract->name}}</span></td>
                                    <td><span >{{$invoice_automation->invoice->contract->contractor->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->user->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->user->role->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->number}}</span></td>
                                    <td><span>{{verta($invoice_automation->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($invoice_automation->updated_at)->format("Y/n/d")}}</span></td>
                                    <td><a href="{{route("InvoiceAutomation.details",$invoice_automation->invoice->id)}}" class="btn btn-sm btn-info iran_yekan">مشاهده</a></td>
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
                            <input type="search" data-table_id="outbox_table" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه، پیمان و پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover iran_yekan index_table main_table" id="outbox_table" data-filter='[1,2,3]'>
                            <thead class="thead-bg-color">
                            <tr>
                                <th scope="col">شماره</th>
                                <th scope="col">پروژه</th>
                                <th scope="col">پیمان</th>
                                <th scope="col">پیمانکار</th>
                                <th scope="col">نام ایجاد کننده</th>
                                <th scope="col">سمت ایجاد کننده</th>
                                <th scope="col"> شماره وضعیت</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">موقعیت</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ارسال</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoice_automations_outbox as $invoice_automation)
                                <tr data-details_route="{{route("InvoiceAutomation.sent.details",$invoice_automation->invoice->id)}}" v-on:dblclick="invoice_details_navigation">
                                    <td><span>{{$invoice_automation->id}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->contract->project->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->contract->name}}</span></td>
                                    <td><span >{{$invoice_automation->invoice->contract->contractor->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->user->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->user->role->name}}</span></td>
                                    <td><span>{{$invoice_automation->invoice->number}}</span></td>
                                    <td>
                        <span>
                            @if($invoice_automation->is_finished)
                                پرداخت شده
                            @else
                                در جریان
                            @endif
                        </span>
                                    </td>
                                    <td>
                        <span>
                            @if($invoice_automation->current_role_id <> 0)
                                {{\App\Models\Role::query()->findOrFail($invoice_automation->current_role_id)->name}}
                            @else
                                تکمیل شده
                            @endif
                        </span>
                                    </td>
                                    <td><span>{{verta($invoice_automation->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($invoice_automation->updated_at)->format("Y/n/d")}}</span></td>
                                    <td><a class="print_anchor" href="{{route("InvoiceAutomation.print",$invoice_automation->invoice_id)}}" target="_blank"><i class="fa fa-print fa-1_4x"></i></a></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">3</div>
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
