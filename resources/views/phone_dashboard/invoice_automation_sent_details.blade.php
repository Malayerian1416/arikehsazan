@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"جزئیات صورت وضعیت تایید و ارسال شده پیمان " . $invoice->contract->name . " از پروژه " . $invoice->contract->project->name}}</span>
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
    <form>
        @csrf
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات پیمان</h6>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه
                </label>
                <input class="form-control iran_yekan text-center bg-white" type="text" readonly value="{{$invoice->contract->project->name}}"/>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="contract_id">پیمان</label>
                <input class="form-control iran_yekan text-center bg-white" type="text" readonly value="{{$invoice->contract->name}}"/>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="contract_branch">رشته پیمان</label>
                <input class="form-control iran_yekan text-center bg-white" type="text" readonly value="{{$invoice->contract->category->branch->branch}}"/>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="contract_category">سرفصل پیمان</label>
                <input class="form-control iran_yekan text-center bg-white" readonly type="text" value="{{$invoice->contract->category->category}}">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="contractor_name">پیمانکار</label>
                <input class="form-control iran_yekan text-center bg-white" readonly type="text" value="{{$invoice->contract->contractor->name}}">
            </div>
            <div class="form-group col-md-12 col-lg-2 col-xl-1">
                <label class="col-form-label iran_yekan black_color" for="invoice_number">وضعیت</label>
                <input class="form-control iran_yekan text-center bg-white" readonly type="text" value="{{$invoice->number}}">
            </div>
            <div class="form-group col-md-12 col-lg-2 col-xl-1">
                <label class="col-form-label iran_yekan black_color" for="invoice_number">قطعی</label>
                <input class="form-control iran_yekan text-center bg-white" readonly type="text" value="@if($invoice->is_final)بلی@elseخیر@endif">
            </div>
        </div>
        <div class="form-row border rounded mt-5" id="invoice_information">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">مشاهده و ویرایش اطلاعات دریافتی</h6>
            </div>
            <div class="table-responsive col-12 pt-3">
                <table class="table table-bordered iran_yekan text-center invoice_automation_table">
                    <thead class="bg-dark white_color">
                    <tr>
                        <th colspan="{{count($invoice->automation_amounts)}}" scope="col" class="border-0">کـــــارکــــرد</th>
                        <th scope="col" class="bg-white border-0" style="border-top: none"></th>
                        <th colspan="{{count($invoice->automation_amounts)}}" class="border-0" scope="col">بـــهــاء جــزء(ریال)</th>
                        <th colspan="{{count($invoice->automation_amounts)}}" class="border-0" scope="col">بـــهــاء کــل(ریال)</th>
                    </tr>
                    <tr>
                        @forelse($invoice->automation_amounts as $automation_amount)
                            <th scope="col">{{$automation_amount->user->role->name}}</th>
                        @empty
                        @endforelse
                        <th scope="col">واحد</th>
                        @forelse($invoice->automation_amounts as $automation_amount)
                            <th scope="col">{{$automation_amount->user->role->name}}</th>
                        @empty
                        @endforelse
                        @forelse($invoice->automation_amounts as $automation_amount)
                            <th scope="col">{{$automation_amount->user->role->name}}</th>
                        @empty
                        @endforelse
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @forelse($invoice->automation_amounts as $quantity)
                            <td class="@if($main_amounts && $quantity->user_id == $main_amounts->user_id) bg-important white_color @endif">{{$quantity->quantity}}</td>
                        @empty
                        @endforelse
                        <td>{{$invoice->contract->unit->name}}</td>
                        @forelse($invoice->automation_amounts as $amount)
                            <td class="@if($main_amounts && $amount->user_id == $main_amounts->user_id) bg-important white_color @endif">{{number_format($amount->amount)}}</td>
                        @empty
                        @endforelse
                        @forelse($invoice->automation_amounts as $total_amount)
                            <td class="@if($main_amounts && $total_amount->user_id == $main_amounts->user_id) bg-important white_color @endif">{{number_format($total_amount->quantity * $total_amount->amount)}}</td>
                        @empty
                        @endforelse
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 col-lg-6 d-flex flex-column">
                <table id="extra_work_table" class="table table-bordered table-success invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr class="bg-dark white_color">
                        <th scope="col" colspan="2" style="padding: 0.3rem" class="border border-secondary">اضافه کار ثبت شده</th>
                    </tr>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ(ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->extras as $extra)
                        <tr>
                            <td style="width: 65%"><span class="text-center iran_yekan">{{$extra->description}}</span></td>
                            <td style="width: 35%"><input class="text-center iran_yekan readonly_input" type="text" readonly value="{{number_format($extra->amount)}}"/></td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr class="bg-dark white_color">
                        <th class="border border-secondary">جمع کل</th>
                        <th class="border border-secondary">{{number_format(array_sum($invoice->extras->pluck("amount")->toArray()))}}</th>
                    </tr>
                    </tfoot>
                </table>
                <table id="deduction_work_table" class="table table-bordered table-danger invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr class="bg-dark white_color">
                        <th scope="col" colspan="2" style="padding: 0.3rem" class="border border-secondary">کسر کار ثبت شده</th>
                    </tr>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ(ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->deductions as $deduction)
                        <tr>
                            <td style="width: 65%"><span class="text-center iran_yekan">{{$deduction->description}}</span></td>
                            <td style="width: 35%"><input class="text-center iran_yekan readonly_input" type="text" readonly value="{{number_format($deduction->amount)}}"/></td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr class="bg-dark white_color">
                        <th class="border border-secondary">جمع کل</th>
                        <th class="border border-secondary">{{number_format(array_sum($invoice->deductions->pluck("amount")->toArray()))}}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <table class="table table-bordered invoice_payable_summary_table text-center iran_yekan">
                    <thead>
                    <tr class="bg-dark white_color">
                        <th scope="col" colspan="3" style="padding: 0.3rem">مجموع قابل پرداخت</th>
                    </tr>
                    <tr>
                        <th>سمت</th>
                        <th>جمع کل(ریال)</th>
                        <th>پیشنهاد پرداخت(ریال)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->automation_amounts as $total_payable)
                        <tr>
                            <td class="@if($main_amounts && $total_payable->user_id == $main_amounts->user_id) bg-important @endif"><span class="text-center iran_yekan">{{$total_payable->user->role->name}}</span></td>
                            <td class="@if($main_amounts && $total_payable->user_id == $main_amounts->user_id) bg-important @endif">
                                <span class="text-center iran_yekan bold_font">
                               {{number_format(($total_payable->amount * $total_payable->quantity)+
                                    array_sum($invoice->extras->pluck("amount")->toArray()) -
                                    array_sum($invoice->deductions->pluck("amount")->toArray()))}}
                                </span>
                            </td>
                            <td class="@if($main_amounts && $total_payable->user_id == $main_amounts->user_id) bg-important @endif">
                                <div class="payment_offer_container">
                                    <span class="text-center iran_yekan bg-success white_color" style="width: 25%">{{$total_payable->payment_offer_percent."%"}}</span>
                                    <span class="text-center iran_yekan bold_font" style="width: 75%;">{{number_format($total_payable->payment_offer)}}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                    <tfoot class="bg-dark">
                    <tr>
                        <th class="border border-secondary"><span class="iran_yekan bold_font white_color">جمع کل</span></th>
                        <th colspan="2" class="white_color border border-secondary" style="font-size: 13px;font-weight: 800">
                            @if($main_amounts)
                                {{number_format(($main_amounts->quantity * $main_amounts->amount) + array_sum($invoice->extras->pluck("amount")->toArray()) - array_sum($invoice->deductions->pluck("amount")->toArray()))}}
                            @else
                                {{"ثبت نشده"}}
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th class="border border-secondary"><span class="iran_yekan bold_font white_color">قابل پرداخت</span></th>
                        <th colspan="2" class="white_color border border-secondary" style="font-size: 13px;font-weight: 800">
                            @if($main_amounts)
                                {{number_format($main_amounts->payment_offer)}}
                            @else
                                {{"ثبت نشده"}}
                            @endif
                        </th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-row border rounded mt-4 mb-4" id="invoice_details">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات اتوماسیون</h6>
            </div>
            @if($invoice->comments->isNotEmpty())
                <div class="form-group col-12">
                    <label class="col-form-label iran_yekan black_color" for="project_name">توضیحات ثبت شده</label>
                    <div class="comments_container">
                        @forelse($invoice->comments as $comment)
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
            @if($invoice->signs->isNotEmpty())
                <div class="form-group col-12">
                    <label class="col-form-label iran_yekan black_color" for="project_name">امضاء شده توسط</label>
                    <div class="sign_container">
                        @forelse($invoice->signs as $sign)
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
