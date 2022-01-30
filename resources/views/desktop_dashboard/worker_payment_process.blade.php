@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
@endsection
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
    <form id="create_form" action="{{route("WorkerPayments.payment_process",$worker_automation->id)}}" method="post" v-on:submit="submit_create_form" enctype="multipart/form-data">
        @csrf
        @method("put")
        <div class="table-responsive">
            <table class="table table-bordered w-100">
                <tbody>
                <tr>
                    <th colspan="3" class="text-muted iran_yekan text-center">برداشت از حساب بانکی</th>
                </tr>
                <tr class="bg-light">
                    <td style="width: 40%">
                        <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="5" data-live-search="true" id="bank_account" name="bank_account" v-on:change="related_data_search" data-type="bank_account_information" ref="parent_select">
                            @forelse($bank_accounts as $bank_account)
                                <option value="{{$bank_account->id}}" @if(array_sum($bank_account->docs->pluck("amount")->toArray()) < $worker_automation->amount) disabled style="color: red" @endif data-deposit="{{array_sum($bank_account->docs->pluck("amount")->toArray())}}">{{$bank_account->name." (".number_format(array_sum($bank_account->docs->pluck("amount")->toArray()))." ریال)"}}</option>
                            @empty
                            @endforelse
                        </select>
                    </td>
                    <td style="width: 60%" colspan="2">
                        <div class="row no-gutters">
                            <div class="col-4">
                                <select class="form-control select_picker iran_yekan" title="پرداخت با چک" id="checks" data-size="5" data-live-search="true" v-model="related_data_select" name="check_id">
                                    <option v-for='search in searches' v-bind:value="search.id">@{{ search.sayyadi + "/" +search.serial }}</option>
                                </select>
                            </div>
                            <div class="col-4 pr-1 pl-1">
                                <input class="form-control iran_yekan text-center persian_date" readonly type="text" id="check_date" name="check_date" placeholder="تاریخ وصول">
                            </div>
                            <div class="col-4 pr-1 pl-1">
                                <input class="form-control iran_yekan text-center masked" type="text" data-mask="00000000000" id="check_number" name="check_number" placeholder="شماره چک">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-muted iran_yekan text-center">{{"واریز به حساب بانکی ".$worker_automation->contractor->name}}</th>
                </tr>
                <tr class="bg-light">
                    <td style="width: 40%">
                        <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="5" data-live-search="true" id="contractor_bank" name="contractor_bank" v-on:change="get_contractor_bank_information" data-type="contractor_bank_information" ref="parent_select">
                            @forelse($worker_automation->contractor->banks as $bank)
                                <option data-options="{{json_encode(["card"=>$bank->card,"account"=>$bank->account,"sheba"=>$bank->sheba])}}" value="{{$bank->name}}">{{$bank->name}}</option>
                            @empty
                            @endforelse
                        </select>
                    </td>
                    <td style="width: 60%" colspan="2">
                        <div class="d-flex flex-row align-items-center justify-content-around">
                            <div>
                                <input id="deposit_kind_cash" checked type="radio" name="deposit_kind" value="check" v-on:change="deposit_kind_change">
                                <label id="deposit_kind_cash_label" class="iran_yekan" for="deposit_kind_cash">چک</label>
                            </div>
                            <div>
                                <input id="deposit_kind_card" disabled type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                <label id="deposit_kind_card_label" class="iran_yekan" for="deposit_kind_card" v-on:click="copy_bank_information" data-copy="">کارت</label>
                            </div>
                            <div>
                                <input id="deposit_kind_account" disabled type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                <label id="deposit_kind_account_label" class="iran_yekan" for="deposit_kind_account" v-on:click="copy_bank_information" data-copy="">حساب</label>
                            </div>
                            <div>
                                <input id="deposit_kind_sheba" disabled type="radio" name="deposit_kind" value="" v-on:change="deposit_kind_change">
                                <label id="deposit_kind_sheba_label" class="iran_yekan" for="deposit_kind_sheba" v-on:click="copy_bank_information" data-copy="">شبا</label>
                            </div>
                            <div>
                                <input id="deposit_kind_cash" type="radio" name="deposit_kind" value="cash" v-on:change="deposit_kind_change">
                                <label id="deposit_kind_cash_label" class="iran_yekan" for="deposit_kind_cash">نقدی</label>
                            </div>
                            <input type="hidden" name="deposit_kind_number" readonly v-model="deposit_kind_number">
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
