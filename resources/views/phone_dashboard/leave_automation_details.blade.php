@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"جزئیات مرخصی"}}</span>
@endsection
@section('content')
    @if($errors->any())
        <div class="iran_yekan alert alert-danger alert-dismissible fade show" role="alert">
            <h6 style="font-weight: 700">
                <i class="fa fa-times-circle" style="color: #ff0000;min-width: 30px;vertical-align: middle;text-align:center;font-size: 1.5rem"></i>
                در هنگام ذخیره صورت وضعیت، خطا(های) زیر رخ داده است :
            </h6>
            <ul>
                {!! implode('', $errors->all('<li>:message</li>')) !!}
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <form id="send_form" action="{{route("LeaveAutomation.automate_sending",$leave->automation->id)}}" method="post" data-type="send" v-on:submit="submit_form">
        @csrf
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات دریافتی</h6>
            </div>
            @if($type == "Hourly" && $leave->arrival == null)
                <div class="form-group col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <p class="iran_yekan text-justify m-0">
                            در حال حاضر به دلیل مشخص نبودن زمان پایان مرخصی، امکان تایید وجود ندارد.
                        </p>
                    </div>
                </div>
            @endif
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    کارمند
                </label>
                <input class="form-control iran_yekan text-center" type="text" readonly value="{{$leave->staff->name}}"/>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="contract_id">نوع</label>
                <input class="form-control iran_yekan text-center" type="text" readonly value="@if($leave->automation->automationable_type == "App\Models\DailyLeave") {{"روزانه"}} @elseif($leave->automation->automationable_type == "App\Models\HourlyLeave") {{"ساعتی"}} @endif"/>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="contract_branch">موقعیت مکانی ثبت شده</label>
                <input class="form-control iran_yekan text-center" type="text" readonly value="@if($leave->location) {{$leave->location->name}} @else {{"ندارد"}} @endif"/>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="col-form-label iran_yekan black_color" for="contract_branch">ایجاد کننده</label>
                <input class="form-control iran_yekan text-center" type="text" readonly value="{{$leave->user->name}}"/>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="contract_category">دلیل مرخصی</label>
                <textarea class="form-control iran_yekan text-center" readonly>{{$leave->reason}}</textarea>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="contract_category">مدت مرخصی</label>
                @if($leave->automation->automationable_type == "App\Models\DailyLeave")
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        @forelse($leave->days as $day)
                            <div class="iran_yekan p-2 d-flex flex-column align-items-center justify-content-center border border-success bg-dark date_box">
                                <h2 class="yellow_color">{{$day->day}}</h2>
                                <h4 class="white_color">{{$month_names[$day->month]}}</h4>
                                <h6 class="text-muted-light">{{$day->year}}</h6>
                            </div>
                        @empty
                        @endforelse
                    </div>
                @elseif($leave->automation->automationable_type == "App\Models\HourlyLeave")
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex flex-row align-items-center justify-content-center">
                            <div style="min-width: 103px;min-height: 93px" class="iran_yekan p-3 d-flex mr-3 flex-column align-items-center justify-content-center border border-success bg-dark date_box">
                                <h5 class="white_color pb-3">پایان مرخصی</h5>
                                <h1 class="yellow_color m-0">
                                    @if($leave->automation->automationable->arrival)
                                        {{$leave->automation->automationable->arrival}}
                                    @else
                                        {{"??:??"}}
                                    @endif
                                </h1>
                            </div>
                            <i class="fa fa-arrow-right fa-3x"></i>
                            <div style="min-width: 103px;min-height: 93px" class="iran_yekan p-3 d-flex ml-3 flex-column align-items-center justify-content-center border border-success bg-dark date_box">
                                <h5 class="white_color pb-3">شروع مرخصی</h5>
                                <h1 class="yellow_color m-0">{{$leave->automation->automationable->departure}}</h1>
                            </div>
                        </div>
                        <div class="bg-dark iran_yekan mt-3 p-3" style="min-width: 150px;">
                            <h5 class="text-center white_color pb-3">مدت زمان مرخصی</h5>
                            <h1 class="text-center yellow_color m-0">
                                @if($leave->automation->automationable->arrival)
                                    {{gmdate("H:i",strtotime($leave->automation->automationable->arrival) - strtotime($leave->automation->automationable->departure))}}
                                @else
                                    {{"??:??"}}
                                @endif
                            </h1>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-group col-12">
                <label class="col-form-label iran_yekan black_color" for="comment">ثبت توضیحات</label>
                <textarea class="form-control new_invoice_comment_box" v-model="new_invoice_comment" name="comment"></textarea>
            </div>
        </div>
        <div class="form-row border rounded mt-4 mb-4" id="invoice_details">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات اتوماسیون</h6>
            </div>
            @if($docs)
                <div class="form-group col-12">
                    <button type="button" class="btn btn-outline-info iran_yekan" data-modal_name="docs_modal" v-on:click="show_modal">
                        <i class="fa fa-paperclip fa-1_4x"></i>
                        مدارک ارسال شده
                    </button>
                </div>
            @endif
            @if($leave->automation->comments->isNotEmpty())
                <div class="form-group col-12">
                    <label class="col-form-label iran_yekan black_color" for="project_name">توضیحات ثبت شده</label>
                    <div class="comments_container">
                        @forelse($leave->automation->comments as $comment)
                            <div class="comment_box iran_yekan">
                                <div class="commenter">
                                    <i class="fa fa-user-circle fa-2x mr-2"></i>
                                    <span class="text-muted">{{$comment->user->name."(".$comment->user->role->name.")"}}</span>
                                </div>
                                <p class="mt-2 comment">{{$comment->comment}}</p>
                                <span class="time-left" dir="ltr">{{verta($comment->created_at)->format("Y/m/d H:i:s")}}</span>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            @endif
            @if($leave->automation->signs->isNotEmpty())
                <div class="form-group col-12">
                    <label class="col-form-label iran_yekan black_color" for="project_name">امضاء شده توسط</label>
                    <div class="sign_container">
                        @forelse($leave->automation->signs as $sign)
                            <div class="sign_box iran_yekan bg-light mr-4">
                                <i class="fa fa-user-circle fa-2x mr-2"></i>
                                <span class="text-muted">{{$sign->user->role->name}}</span>
                                <span>{{$sign->user->name}}</span>
                                <span class="text-muted" dir="ltr" style="font-size: 10px">{{verta($sign->created_at)->format("Y/m/d H:i:s")}}</span>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button class="iran_yekan btn btn-outline-info mr-2" v-on:click="show_contract_details_modal">
            <i class="fa fa-list-alt button_icon"></i>
            <span>سوابق مرخصی</span>
        </button>
        @if($main_role == auth()->user()->role->id)
            @if($type == "Hourly" && $leave->arrival != null || $type == "Daily")
                @can("approve","LeaveAutomation")
                <form id="approve_form" data-type="approve" action="{{route("LeaveAutomation.approve",$leave->automation->id)}}" method="post" v-on:submit="submit_form">
                    @csrf
                    <button type="submit" form="approve_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                        <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                        <i v-show="button_not_loading" class="fa fa-money-bill-wave button_icon"></i>
                        <span v-show="button_not_loading">تایید و اتمام</span>
                    </button>
                </form>
                @endcan
            @endif
        @else
            @can("send","LeaveAutomation")
            @if($type == "Hourly" && $leave->arrival != null || $type == "Daily")
                <button type="submit" form="send_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                    <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                    <i v-show="button_not_loading" class="fa fa-check-square button_icon"></i>
                    <span v-show="button_not_loading">تایید و ارسال</span>
                </button>
            @endif
            @endcan
        @endif
        @can("reject","LeaveAutomation")
        <form id="reject_form" data-type="reject" action="{{route("LeaveAutomation.reject",$leave->automation->id)}}" method="post" v-on:submit="submit_form">
            @csrf
            <button type="submit" form="reject_form" class="btn btn-outline-warning iran_yekan mr-2 submit_button">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-times button_icon"></i>
                <span v-show="button_not_loading">عدم تایید و اتمام</span>
            </button>
        </form>
        @endcan
        @can("refer","LeaveAutomation")
        <form id="refer_form" data-type="refer" action="{{route("LeaveAutomation.refer",$leave->automation->id)}}" method="post" v-on:submit="submit_form">
            @csrf
            <button type="submit" form="refer_form" class="btn btn-outline-danger iran_yekan mr-2 submit_button">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-arrow-left button_icon"></i>
                <span v-show="button_not_loading">ارجاع</span>
            </button>
        </form>
        @endcan

    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="contract_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{"اطلاعات مرخصی " . $leave->staff->name}}</h6>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped detail_table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">شماره</th>
                                <th scope="col">نوع</th>
                                <th scope="col">توسط</th>
                                <th scope="col">مدت</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">موقعیت</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ارسال</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($leave_history as $history)
                                <tr>
                                    <td>{{$history->automationable->id}}</td>
                                    <td>
                                        @if($history->automationable_type == "App\Models\DailyLeave")
                                            {{"روزانه"}}
                                        @elseif($history->automationable_type == "App\Models\HourlyLeave")
                                            {{"ساعتی"}}
                                        @else
                                            {{"نامشخص"}}
                                        @endif
                                    </td>
                                    <td>{{$history->automationable->user->name}}</td>
                                    <td>
                                        @if($history->automationable_type == "App\Models\DailyLeave")
                                            {{count($history->automationable->days->toArray())." روز"}}
                                        @elseif($history->automationable_type == "App\Models\HourlyLeave")
                                            {{strtotime($history->automationable->arrival) - strtotime($history->automationable->departure)." - ".$history->automationable->year." ".$month_names[$history->automationable->month]." " .$history->automationable->day}}
                                        @else
                                            {{"نامشخص"}}
                                        @endif
                                    </td>
                                    <td>
                                        <span>
                                            @if($history->is_finished)
                                                @if($history->automationable->is_approved)
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
                                            @if($history->current_role_id <> 0)
                                                {{\App\Models\Role::query()->findOrFail($history->current_role_id)->name}}
                                            @else
                                                {{"تکمیل شده"}}
                                            @endif
                                        </span>
                                    </td>
                                    <td><span>{{verta($history->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($history->updated_at)->format("Y/n/d")}}</span></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr>

                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade iran_yekan" id="docs_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{"مدارک ارسال شده"}}</h6>
                </div>
                <div class="modal-body">
                    @if($docs)
                        @forelse($docs as $doc)
                            <a class="print_anchor" download href="@if($type == "Daily") {{"/storage/daily_leave_docs/$doc"}} @elseif($type == "Hourly") {{"/storage/hourly_leave_docs/$doc"}} @endif">
                                <h5 class="iran_yekan">
                                    {{$doc}}
                                </h5>
                            </a>
                        @empty
                        @endforelse
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
