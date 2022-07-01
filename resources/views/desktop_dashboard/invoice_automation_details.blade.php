@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    {{"جزئیات صورت وضعیت پیمان " . $invoice->contract->name . " از پروژه " . $invoice->contract->project->name}}
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
    <form @if($invoice->automation->next_role_id != 0) id="create_form" action="{{route("InvoiceAutomation.automate_sending",$invoice->id)}}" method="post" data-type="create" v-on:submit="submit_form" @else id="pay_form" action="{{route("InvoiceAutomation.payment_process",$invoice->id)}}" method="post" data-type="pay" v-on:submit="submit_form" enctype="multipart/form-data" @endif>
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
                        <th colspan="{{count($invoice->automation_amounts)}}" scope="col" class="border border-muted">کـــــارکــــرد</th>
                        <th scope="col" class="bg-white border-0" style="border-top: none"></th>
                        <th colspan="{{count($invoice->automation_amounts)}}" class="border border-muted" scope="col">بـــهــاء جــزء(ریال)</th>
                        <th colspan="{{count($invoice->automation_amounts)}}" class="border border-muted" scope="col">بـــهــاء کــل(ریال)</th>
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
                        <th colspan="2" class="white_color border border-secondary" style="font-size: 15px;font-weight: 800;font-style: italic">
                            @if($main_amounts)
                                {{number_format(($main_amounts->quantity * $main_amounts->amount) + array_sum($invoice->extras->pluck("amount")->toArray()) - array_sum($invoice->deductions->pluck("amount")->toArray()))}}
                            @else
                                {{"ثبت نشده"}}
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th class="border border-secondary"><span class="iran_yekan bold_font white_color">قابل پرداخت</span></th>
                        <th colspan="2" class="white_color border border-secondary" style="font-size: 15px;font-weight: 800;font-style: italic">
                            @if($main_amounts)
                                {{number_format($main_amounts->payment_offer)}}
                            @else
                                {{"ثبت نشده"}}
                            @endif
                        </th>
                    </tr>
                    @if($invoice->automation->next_role_id != 0)
                        @if($invoice_flow_permissions->quantity == 1 || $invoice_flow_permissions->amount == 1 || $invoice_flow_permissions->payment_offer == 1)
                            <tr class="bg-light">
                                <td colspan="3">
                                    <button type="button" v-on:click="new_invoice_amounts_modal" class="btn btn-outline-primary form-control" style="font-size: 13px">
                                        <i class="fa fa-plus" style="vertical-align: middle;font-size: 13px"></i>
                                        ثبت مقادیر جدید اتوماسیون
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr class="bg-light">
                            <td class="border-0"><span class="iran_yekan bold_font">مبلغ پرداخت شده</span></td>
                            <td colspan="2" class="border-0">
                                @if($main_amounts)
                                    <input class="form-control iran_yekan number_format text-center" data-total_amount="{{$main_amounts->payment_offer}}" style="font-size: 13px;font-weight: 800" name="total_amount_payed" type="text" value="{{number_format($main_amounts->payment_offer)}}" v-on:input="invoice_automation_total_payment">
                                @else
                                    {{"غیر قابل پرداخت"}}
                                @endif
                            </td>
                        </tr>
                        @if($main_amounts)
                            <tr>
                                <th colspan="3" class="white_color">{{"واریز به حساب بانکی ".$invoice->contract->contractor->name}}</th>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="3">
                                    <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="5" data-live-search="true" id="contractor_bank" name="contractor_bank" v-on:change="get_contractor_bank_information">
                                        @forelse($invoice->contract->contractor->banks as $bank)
                                            <option data-options="{{json_encode(["card"=>$bank->card,"account"=>$bank->account,"sheba"=>$bank->sheba,"bank_id" => $bank->id])}}" value="{{$bank->name}}">{{$bank->name}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="3">
                                    <div class="d-flex flex-column align-items-start">
                                        <div class="w-100 mb-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input id="deposit_kind_card" checked type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                                    </div>
                                                    <span class="input-group-text" id="deposit_kind_card_label" style="width: 90px">واریز به کارت</span>
                                                </div>
                                                <input id="deposit_kind_card_number" type="text" class="form-control text-center masked" data-mask="0000-0000-0000-0000" style="font-size: 13px" aria-label="Text input with radio button">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i id="card-copy" class="fa fa-copy fa-1_4x" title="کپی" data-copy="" style="cursor: pointer" v-on:click="copy_bank_information"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i id="edit_card" class="fa fa-edit fa-1_4x" data-contractor_id="{{$invoice->contract->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 mb-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input id="deposit_kind_sheba" type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                                    </div>
                                                    <span class="input-group-text" id="deposit_kind_sheba_label" style="width: 90px">واریز به شبا</span>
                                                </div>
                                                <input id="deposit_kind_sheba_number" type="text" class="form-control text-center masked" data-mask="IR00-0000-0000-0000-0000-0000-00" style="font-size: 13px" aria-label="Text input with radio button">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i id="sheba-copy" class="fa fa-copy fa-1_4x" title="کپی" data-copy="" style="cursor: pointer" v-on:click="copy_bank_information"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i id="edit_sheba" class="fa fa-edit fa-1_4x" data-contractor_id="{{$invoice->contract->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 mb-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input id="deposit_kind_account" type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                                    </div>
                                                    <span class="input-group-text" id="deposit_kind_account_label" style="width: 90px">واریز به حساب</span>
                                                </div>
                                                <input id="deposit_kind_account_number" type="text" class="form-control text-center masked" data-mask="00000000000000000000000000" style="font-size: 13px" aria-label="Text input with radio button">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i id="account-copy" class="fa fa-copy fa-1_4x" title="کپی" data-copy="" style="cursor: pointer" v-on:click="copy_bank_information"></i>
                                                    </span>
                                                    <span class="input-group-text">
                                                        <i id="edit_account" class="fa fa-edit fa-1_4x" data-contractor_id="{{$invoice->contract->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 mb-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input id="deposit_kind_cash" type="radio" name="deposit_kind" value="cash" v-on:change="deposit_kind_change">
                                                    </div>
                                                    <span class="input-group-text" id="deposit_kind_account_label" style="width: 90px">چک</span>
                                                </div>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        شماره چک
                                                    </div>
                                                </div>
                                                <input id="deposit_kind_account_number" type="text" class="form-control text-center masked" style="font-size: 13px" aria-label="Text input with radio button">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        تاریخ وصول
                                                    </div>
                                                </div>
                                                <input class="form-control iran_yekan text-center persian_date" type="text" id="check_date" name="check_date" placeholder="تاریخ وصول">
                                            </div>
                                        </div>
                                        <div class="w-100 mb-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input id="deposit_kind_cash" type="radio" name="deposit_kind" value="cash" v-on:change="deposit_kind_change">
                                                    </div>
                                                    <span class="input-group-text" id="deposit_kind_account_label" style="width: 90px">نقدی</span>
                                                </div>
                                                <input id="deposit_kind_account_number" type="text" class="form-control text-center masked" readonly style="font-size: 13px" aria-label="Text input with radio button">
                                            </div>
                                        </div>
                                        <input type="hidden" name="deposit_kind_number" readonly v-model="deposit_kind_number">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3" class="white_color">برداشت از حساب بانکی</th>
                            </tr>
                            <tr class="bg-light">
                                <td style="width: 40%">
                                    <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="5" data-live-search="true" id="bank_account" name="bank_account" v-on:change="related_data_search" data-type="bank_account_information" ref="parent_select">
                                        @forelse($bank_accounts as $bank_account)
                                            <option value="{{$bank_account->id}}" @if(array_sum($bank_account->docs->pluck("amount")->toArray()) < $main_amounts->payment_offer) disabled style="color: red" @endif data-deposit="{{array_sum($bank_account->docs->pluck("amount")->toArray())}}">{{$bank_account->name." (".number_format(array_sum($bank_account->docs->pluck("amount")->toArray()))." ریال)"}}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </td>
                                <td style="width: 60%" colspan="2">
                                    <div class="row no-gutters">
                                        <div class="col-12">
                                            <select class="form-control select_picker iran_yekan" title="انتخاب دسته چک" id="checks" data-size="5" data-live-search="true" v-model="related_data_select" name="check_id">
                                                <option v-for='search in searches' v-bind:value="search.id">@{{ search.sayyadi + "/" +search.serial }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="3">
                                    <div class="d-flex flex-row justify-content-start align-items-center align-content-stretch">
                                        <input type="text" class="form-control iran_yekan text-center mr-2" name="payment_receipt_number" placeholder="شماره رسید پرداخت">
                                        <input type="file" hidden name="payment_receipt_scan" v-on:change="file_browser_change">
                                        <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="بارگذاری اسکن رسید پرداخت">
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endif
                    </tfoot>
                </table>
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
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button class="iran_yekan btn btn-outline-info mr-2" v-on:click="show_contract_details_modal">
            <i class="fa fa-list-alt button_icon"></i>
            <span>اطلاعات پیمان</span>
        </button>
        <button class="iran_yekan btn btn-outline-info mr-2" v-on:click="show_contractor_details_modal">
            <i class="fa fa-user button_icon"></i>
            <span>اطلاعات پیمانکار</span>
        </button>
        <a class="iran_yekan btn btn-outline-warning mr-2 print_anchor" target="_blank" href="{{route("InvoiceAutomation.print",$invoice->id)}}">
            <i class="fa fa-print button_icon"></i>
            <span>چاپ</span>
        </a>
        @if($invoice->automation->next_role_id == 0)
            @if($main_amounts)
                <button type="submit" form="pay_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                    <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                    <i v-show="button_not_loading" class="fa fa-money-bill-wave button_icon"></i>
                    <span v-show="button_not_loading">پرداخت و اتمام</span>
                </button>
            @endif
        @else
            <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-check-square button_icon"></i>
                <span v-show="button_not_loading">تایید و ارسال</span>
            </button>
        @endif
        <form id="refer_form" action="{{route("InvoiceAutomation.refer",$invoice->id)}}" data-type="refer" v-on:submit="submit_form" method="post">
            @csrf
            <button type="submit" form="refer_form" class="btn btn-outline-danger iran_yekan mr-2 submit_button">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-arrow-left button_icon"></i>
                <span v-show="button_not_loading">ارجاع</span>
            </button>
        </form>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="contract_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{"اطلاعات تفصیلی پیمان " . $invoice->contract->name}}</h6>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped detail_table">
                            <thead class="thead-dark">
                            <tr>
                                <th>شماره وضعیت</th>
                                <th>کارکرد</th>
                                <th>مبلغ</th>
                                <th>اضافه کاری</th>
                                <th>کسر کار</th>
                                <th>جمع نهایی</th>
                                <th>گردش</th>
                                <th>پرداختی</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contract_details as $contract_detail)
                                <tr>
                                    <td class="align_middle">{{$contract_detail->number}}</td>
                                    <td>
                                        <select class="form-control">
                                            @forelse($contract_detail->automation_amounts as $quantity)
                                                <option class="@if($quantity->is_main) bg-important @endif" style="padding: 5px">{{$quantity->user->role->name." : ".$quantity->quantity}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control">
                                            @forelse($contract_detail->automation_amounts as $amount)
                                                <option class="@if($amount->is_main) bg-important @endif" style="padding: 5px">{{$amount->user->role->name." : ".number_format($amount->amount)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>
                                    <td>
                                        @if($contract_detail->extras->isNotEmpty())
                                            {{number_format(array_sum($contract_detail->extras->pluck("amount")->toArray()))}}
                                        @else
                                            {{0}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($contract_detail->deductions->isNotEmpty())
                                            {{number_format(array_sum($contract_detail->deductions->pluck("amount")->toArray()))}}
                                        @else
                                            {{0}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($main_amount = $contract_detail->automation_amounts->where("is_main","=",1)->first())
                                            {{number_format(($main_amount->amount * $main_amount->quantity) +
                                               array_sum($contract_detail->extras->pluck("amount")->toArray()) -
                                            array_sum($contract_detail->deductions->pluck("amount")->toArray()))}}
                                        @else
                                            تعیین نشده
                                        @endif
                                    </td>
                                    <td>
                                        @if($contract_detail->automation->is_finished)
                                            تکمیل شده
                                        @else
                                            @if($invoice->automation->current_role_id != 0)
                                                {{\App\Models\Role::query()->findOrFail($invoice->automation->current_role_id)->name}}</td>
                                    @else
                                        ارجاع شده
                                        @endif
                                        @endif
                                        </td>
                                        <td>
                                            @if($contract_detail->payments->isNotEmpty())
                                                {{number_format(array_sum($contract_detail->payments->pluck('amount_payed')->toArray()))}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot class="thead-dark">
                            <tr>
                                <th colspan="2">
                                    جمع کارکرد تایید شده :
                                    @php($quantity_sum=0)
                                    @forelse($contract_details as $contract_detail)
                                        @if($quantity_sum += array_sum($contract_detail->automation_amounts->where("is_main","=",1)->pluck('quantity')->toArray()))
                                        @endif
                                    @empty
                                    @endforelse
                                    <span class="bg-danger white_color p-2">{{$quantity_sum." ".$contract_detail->contract->unit->name}}</span>
                                </th>
                                <th colspan="2">
                                    جمع کل تایید شده :
                                    @php($total_amount=0)
                                    @forelse($contract_details as $contract_detail)
                                        @if($main_amount = $contract_detail->automation_amounts->where("is_main","=",1)->first())
                                            @php($total_amount += ($main_amount->amount * $main_amount->quantity) +
                                                   array_sum($contract_detail->extras->pluck("amount")->toArray()) -
                                                array_sum($contract_detail->deductions->pluck("amount")->toArray()))
                                        @endif
                                    @empty
                                    @endforelse
                                    <span class="bg-danger white_color p-2">{{number_format($total_amount)." ریال"}}</span>
                                </th>
                                <th colspan="2">
                                    جمع پرداختی :
                                    @php($total_payed=0)
                                    @forelse($contract_details as $contract_detail)
                                        @if($contract_detail->payments->isNotEmpty())
                                            @php($total_payed += array_sum($contract_detail->payments->pluck('amount_payed')->toArray()))
                                        @endif
                                    @empty
                                    @endforelse
                                    <span class="bg-danger white_color p-2">{{number_format($total_payed)." ریال"}}</span>
                                </th>
                                <th colspan="2">
                                    باقیمانده :
                                    <span class="bg-danger white_color p-2">{{number_format($total_amount - $total_payed)." ریال"}}</span>
                                </th>
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
    <div class="modal fade iran_yekan" id="contractor_details_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{"اطلاعات تفصیلی پیمانکار " . $invoice->contract->contractor->name." (".$invoice->contract->project->name.")"}}</h6>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped detail_table">
                            <thead class="thead-dark">
                            <tr>
                                <th>پیمان</th>
                                <th>شماره وضعیت</th>
                                <th>جمع کل</th>
                                <th>پرداختی</th>
                                <th>باقی مانده</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($all_total_amounts = 0)
                            @php($all_total_payed = 0)
                            @php($remains = 0)
                            @forelse($contractor_details->contract as $contractor_contract_detail)
                                @forelse($contractor_contract_detail->invoices as $contract_detail_invoice)
                                    <tr>
                                        <td>{{$contractor_contract_detail->name}}</td>
                                        <td>{{$contract_detail_invoice->number}}</td>
                                        <td>
                                            @php($total_amount = 0)
                                            @if($amounts = $contract_detail_invoice->automation_amounts->where("is_main","=",1)->first())
                                                {{$total_amount = number_format(($amounts->quantity * $amounts->amount) + array_sum($contract_detail_invoice->extras->pluck('amount')->toArray()) - array_sum($contract_detail_invoice->deductions->pluck('amount')->toArray()))}}
                                            @else
                                                0
                                            @endif
                                            @php($all_total_amounts += str_replace(',','',$total_amount))
                                        </td>
                                        <td>
                                            @php($total_payed = 0)
                                            @if($contract_detail_invoice->payments->isNotEmpty())
                                                {{$total_payed = number_format((array_sum($contract_detail_invoice->payments->pluck('amount_payed')->toArray())))}}
                                            @else
                                                0
                                            @endif
                                            @php($all_total_payed += str_replace(',','',$total_payed))
                                        </td>
                                        <td>
                                            {{$invoice_remain = number_format(str_replace(',','',$total_amount) - str_replace(',','',$total_payed))}}
                                            @php($remains += str_replace(',','',$invoice_remain))
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            @empty
                            @endforelse
                            </tbody>
                            <tfoot class="thead-dark">
                            <tr>
                                <th colspan="2">
                                    جمع طلب کاری :
                                    <span class="bg-danger white_color p-2">{{number_format($all_total_amounts)." ریال"}}</span>
                                </th>
                                <th colspan="2">
                                    جمع پرداختی :
                                    <span class="bg-danger white_color p-2">{{number_format($all_total_payed)." ریال"}}</span>
                                </th>
                                <th colspan="2">
                                    جمع باقی مانده :
                                    <span class="bg-danger white_color p-2">{{number_format($remains)." ریال"}}</span>
                                </th>
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
    <div class="modal fade iran_yekan" id="enter_new_invoice_amounts" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="new_invoice_amounts_form" action="{{route("InvoiceAutomation.amounts",$invoice->id)}}" method="post" data-type="create" v-on:submit="submit_form">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">{{"ثبت مقادیر جدید برای صورت وضعیت"}}</h6>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="thead-dark text-center">
                            <tr>
                                <th class="text-center">سمت</th>
                                <th class="text-center">کارکرد</th>
                                <th class="text-center">مبلغ جزء(ریال)</th>
                                <th class="text-center">مبلغ کل(ریال)</th>
                                <th class="text-center">درصد پیشنهاد پرداخت(ریال)</th>
                                <th class="text-center">پیشنهاد پرداخت(ریال)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoice->automation_amounts as $automation_amount)
                                @if($automation_amount->user_id == \Illuminate\Support\Facades\Auth::id())
                                    <tr>
                                        <td>{{\Illuminate\Support\Facades\Auth::user()->role->name}}</td>
                                        <td>
                                            <input name="quantity" @if(!$invoice_flow_permissions->quantity) readonly @endif class="form-control iran_yekan number_format_dec text-center" id="invoice_automation_quantity" type="text" value="{{$automation_amount->quantity}}" v-model="new_invoice_quantity" v-on:input="new_invoice_total_amount_process"/>
                                        </td>
                                        <td>
                                            <input name="amount" type="text" @if(!$invoice_flow_permissions->amount) readonly @endif class="form-control iran_yekan number_format text-center" id="invoice_automation_amount" value="{{$automation_amount->amount}}" v-model="new_invoice_amount" v-on:input="new_invoice_total_amount_process"/>
                                        </td>
                                        <td><input name="total_amount" readonly type="text" class="form-control iran_yekan number_format text-center" v-model="new_invoice_total_amount"/></td>
                                        <td>
                                            <input name="payment_offer_percent" @if(!$invoice_flow_permissions->payment_offer) readonly @endif type="number" style="height: 30px" class="form-control iran_yekan text-center" :disabled="payment_offer_disabled" v-model="invoice_payment_offer_percent" v-on:input="invoice_payment_offer_percent_change"/>
                                        </td>
                                        <td>
                                            <input name="payment_offer" @if(!$invoice_flow_permissions->payment_offer) readonly @endif type="text" class="form-control iran_yekan text-center number_format" :disabled="payment_offer_disabled" v-model="invoice_payment_offer" v-on:input="invoice_payment_offer_change"/>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="text-muted">
                                        <td>{{$automation_amount->user->role->name}}</td>
                                        <td>{{$automation_amount->quantity}}</td>
                                        <td>{{number_format($automation_amount->amount)}}</td>
                                        <td>{{number_format($automation_amount->amount * $automation_amount->quantity)}}</td>
                                        <td>{{number_format($automation_amount->payment_offer_percent)}}</td>
                                        <td>{{number_format($automation_amount->payment_offer)}}</td>
                                    </tr>
                                @endif
                            @empty
                            @endforelse
                            @if($invoice->automation_amounts->where("user_id","=",\Illuminate\Support\Facades\Auth::id())->isEmpty())
                                <tr>
                                    <td>{{\Illuminate\Support\Facades\Auth::user()->role->name}}</td>
                                    <td>
                                        <input name="quantity" @if(!$invoice_flow_permissions->quantity) readonly @endif class="form-control iran_yekan number_format_dec text-center" id="invoice_automation_quantity" type="text" value="{{$invoice->automation_amounts[0]->quantity}}" v-model="new_invoice_quantity" v-on:input="new_invoice_total_amount_process"/>
                                    </td>
                                    <td>
                                        <input name="amount" type="text" @if(!$invoice_flow_permissions->amount) readonly @endif class="form-control iran_yekan number_format text-center" id="invoice_automation_amount" value="{{$invoice->automation_amounts[0]->amount}}" v-model="new_invoice_amount" v-on:input="new_invoice_total_amount_process"/>
                                    </td>
                                    <td><input name="total_amount" readonly type="text" class="form-control iran_yekan number_format text-center" v-model="new_invoice_total_amount"/></td>
                                    <td>
                                        <input name="payment_offer_percent" @if(!$invoice_flow_permissions->payment_offer) readonly @endif type="number" style="height: 30px" class="form-control iran_yekan text-center" :disabled="payment_offer_disabled" v-model="invoice_payment_offer_percent" v-on:input="invoice_payment_offer_percent_change"/>
                                    </td>
                                    <td>
                                        <input name="payment_offer" @if(!$invoice_flow_permissions->payment_offer) readonly @endif type="text" class="form-control iran_yekan text-center number_format" :disabled="payment_offer_disabled" v-model="invoice_payment_offer" v-on:input="invoice_payment_offer_change"/>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="new_invoice_amounts_form" class="btn btn-primary">ثبت</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
