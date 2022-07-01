@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    عملیات پرداخت اتوماسیون پرداختی کارگری
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
    <div class="container">
    <form id="create_form" action="{{route("WorkerPayments.payment_process",$worker_automation->id)}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method("put")
        <div class="table-responsive iran_yekan">
            <table class="table table-bordered w-100">
                <tbody>
                <tr>
                    <th class="white_color bg-dark" style="width: 20%">{{"واریز به حساب : ".$worker_automation->contractor->name}}</th>
                    <th class="white_color bg-dark" style="width: 13%">{{"مبلغ : ".number_format($worker_automation->amount)." ریال"}}</th>
                    <th class="white_color bg-dark" style="width: 37%">{{"بابت : ".$worker_automation->description}}</th>
                    <th class="white_color bg-dark" style="width: 15%">{{"تاریخ ایجاد : ".verta($worker_automation->created_at)->format("Y/n/d")}}</th>
                    <th class="white_color bg-dark" style="width: 15%">{{"تاریخ پرداخت : ".verta()->format("Y/n/d")}}</th>
                </tr>
                <tr class="bg-light">
                    <td colspan="5">
                        <select class="form-control select_picker iran_yekan" title="نام بانک جهت واریز را انتخاب کنید" data-size="5" data-live-search="true" id="contractor_bank" name="contractor_bank" v-on:change="get_contractor_bank_information">
                            @forelse($worker_automation->contractor->banks as $bank)
                                <option data-options="{{json_encode(["bank_id" => $bank->id,"card"=>$bank->card,"account"=>$bank->account,"sheba"=>$bank->sheba])}}" value="{{$bank->name}}">{{$bank->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </td>
                </tr>
                <tr class="bg-light">
                    <td colspan="5">
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
                                                        <i id="edit_card" class="fa fa-edit fa-1_4x" data-contractor_id="{{$worker_automation->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
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
                                                        <i id="edit_sheba" class="fa fa-edit fa-1_4x" data-contractor_id="{{$worker_automation->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
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
                                                        <i id="edit_account" class="fa fa-edit fa-1_4x" data-contractor_id="{{$worker_automation->contractor->id}}" data-bank_id="" data-value="" title="ویرایش شماره" v-on:click="edit_bank_information" style="cursor: pointer"></i>
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
                </tbody>
            </table>
        </div>
        <div class="table-responsive iran_yekan">
            <table class="table table-bordered w-100">
                <tbody>
                <tr>
                    <th colspan="3" class="white_color bg-dark">برداشت از حساب بانکی</th>
                </tr>
                <tr class="bg-light">
                    <td style="width: 40%">
                        <select class="form-control select_picker iran_yekan" title="نام بانک جهت برداشت را انتخاب کنید" data-size="5" data-live-search="true" id="bank_account" name="bank_account" v-on:change="related_data_search" data-type="bank_account_information" ref="parent_select">
                            @forelse($bank_accounts as $bank_account)
                                <option value="{{$bank_account->id}}" @if(array_sum($bank_account->docs->pluck("amount")->toArray()) < $worker_automation->amount) disabled style="color: red" @endif data-deposit="{{array_sum($bank_account->docs->pluck("amount")->toArray())}}">{{$bank_account->name." (".number_format(array_sum($bank_account->docs->pluck("amount")->toArray()))." ریال)"}}</option>
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
                </tbody>
            </table>
        </div>
    </form>
    </div>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">پرداخت و اتمام</span>
        </button>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
