@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">صورت وضعیت های جدید</span>
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
                                <th scope="col">کارمند</th>
                                <th scope="col">نوع</th>
                                <th scope="col">توسط</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ارسال</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($leave_automations_inbox as $leave_automation)
                                <tr class="@if($leave_automation->is_read == 0) bold_font black_color @else text-muted @endif" data-details_route="{{route("LeaveAutomation.details",[$leave_automation->automationable_id,$leave_automation->automationable_type == "App\Models\DailyLeave"?"Daily":"Hourly"])}}" v-on:dblclick="invoice_details_navigation">
                                    <td><span>{{$leave_automation->id}}</span></td>
                                    <td><span>{{$leave_automation->automationable->staff->name}}</span></td>
                                    <td>
                                        <span>
                                            @if($leave_automation->automationable_type == 'App\Models\DailyLeave')
                                                {{"روزانه"}}
                                            @elseif($leave_automation->automationable_type == 'App\Models\HourlyLeave')
                                                {{"ساعتی"}}
                                            @else
                                                {{"نامشخص"}}
                                            @endif
                                        </span>
                                    </td>
                                    <td><span >{{$leave_automation->automationable->user->name}}</span></td>
                                    <td><span>{{verta($leave_automation->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($leave_automation->updated_at)->format("Y/n/d")}}</span></td>
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
                                <th scope="col">کارمند</th>
                                <th scope="col">نوع</th>
                                <th scope="col">توسط</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">موقعیت</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ارسال</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($leave_automations_outbox as $leave_automation)
                                @if($leave_automation->automationable)
                                    <tr class="@if($leave_automation->is_read == 0) bold_font black_color @else text-muted @endif" data-details_route="{{route("LeaveAutomation.details",[$leave_automation->automationable->id,$leave_automation->automationable_type == "App\Models\DailyLeave"?"Daily":"Hourly"])}}" v-on:dblclick="invoice_details_navigation">
                                        <td><span>{{$leave_automation->id}}</span></td>
                                        <td><span>{{$leave_automation->automationable->staff->name}}</span></td>
                                        <td>
                                        <span>
                                            @if($leave_automation->automationable_type == 'App\Models\DailyLeave')
                                                {{"روزانه"}}
                                            @elseif($leave_automation->automationable_type == 'App\Models\HourlyLeave')
                                                {{"ساعتی"}}
                                            @else
                                                {{"نامشخص"}}
                                            @endif
                                        </span>
                                        </td>
                                        <td><span >{{$leave_automation->automationable->user->name}}</span></td>
                                        <td>
                                        <span>
                                            @if($leave_automation->is_finished)
                                                @if($leave_automation->automationable->is_approved)
                                                    {{"تایید شده"}}
                                                @else
                                                    {{"تایید نشده"}}
                                                @endif
                                            @else
                                                {{"در جریان"}}
                                            @endif
                                        </span>
                                        </td>
                                        <td>
                                        <span>
                                            @if($leave_automation->current_role_id <> 0)
                                                {{\App\Models\Role::query()->findOrFail($leave_automation->current_role_id)->name}}
                                            @else
                                                {{"تکمیل شده"}}
                                            @endif
                                        </span>
                                        </td>
                                        <td><span>{{verta($leave_automation->created_at)->format("Y/n/d")}}</span></td>
                                        <td><span>{{verta($leave_automation->updated_at)->format("Y/n/d")}}</span></td>
                                    </tr>
                                @endif
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

