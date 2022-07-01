@extends('phone_dashboard.p_dashboard')
@section('scripts')
    <script>
        let quantity = {{$invoice->automation_amounts[0]->quantity}};
        let payment_offer_percent = {{$invoice->automation_amounts[0]->payment_offer_percent}};
    </script>
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش وضعیت شماره ".$invoice->number." پیمان ".$invoice->contract->name}}</span>
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
    <form id="update_form" action="{{route("InvoicesLimited.update",$invoice->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات پیمان</h6>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه
                </label>
                <input class="form-control iran_yekan text-center" readonly value="{{$invoice->contract->project->name}}">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_id">
                    پیمان
                </label>
                <input class="form-control iran_yekan text-center" readonly value="{{$invoice->contract->name}}">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contractor_name">پیمانکار</label>
                <input class="form-control iran_yekan text-center" readonly value="{{$invoice->contract->contractor->name}}">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="invoice_number">شماره وضعیت</label>
                <input class="form-control iran_yekan text-center" readonly value="{{$invoice->number}}">
            </div>
        </div>
        <div class="form-row border rounded mt-5" id="invoice_information">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات ثبت شده</h6>
            </div>
            <input type="hidden" name="automation_amount_id" value="{{$invoice->automation_amounts[0]->id}}">
            <div class="form-group col-md-12 col-lg-4">
                <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount_process">میزان کارکرد</label>
                <input class="form-control iran_yekan text-center number_format_dec" type="text" name="quantity" v-model="new_invoice_quantity" v-on:input="new_invoice_total_amount_process">
            </div>
            <div class="form-group col-md-12 col-lg-4">
                <label class="col-form-label iran_yekan black_color" for="new_invoice_unit">واحد</label>
                <input class="form-control iran_yekan text-center" type="text" readonly value="{{$invoice->contract->unit->name}}">
            </div>
            <div class="form-group col-md-12 col-lg-4">
                <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount">پیشنهاد پرداخت(ریال)</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                    <input class="form-control iran_yekan text-center number_format" type="text" name="payment_offer_percent" v-model="invoice_payment_offer_percent" v-on:input="percent_check">
                </div>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount">اضافه کاری های ثبت شده</label>
                <table id="extra_work_table" class="table table-bordered invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ</th>
                        <th>
                            عملیات
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->extras as $extra)
                        <tr>
                            <td style="width: 55%"><input type="text" id="extra_desc_{{$extra->id}}" class="form-control text-center iran_yekan" value="{{$extra->description}}"></td>
                            <td style="width: 35%"><input type="text" id="extra_amount_{{$extra->id}}" class="form-control text-center iran_yekan number_format" value="{{$extra->amount}}"></td>
                            <td>
                                <i class="fa fa-edit index_edit_icon mr-2" data-type="extra" data-action="edit" data-id="{{$extra->id}}" v-on:click="change_extra_deduction_content"></i>
                                <i class="fa fa-trash index_delete_icon" data-type="extra" data-action="delete" data-id="{{$extra->id}}" v-on:click="change_extra_deduction_content"></i>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount">کسر کار های ثبت شده</label>
                <table id="extra_work_table" class="table table-bordered invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ</th>
                        <th>
                            عملیات
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->deductions as $deduction)
                        <tr>
                            <td style="width: 55%"><input type="text" id="deduction_desc_{{$deduction->id}}" class="form-control text-center iran_yekan" value="{{$deduction->description}}"></td>
                            <td style="width: 35%"><input type="text" id="deduction_amount_{{$deduction->id}}" class="form-control text-center iran_yekan number_format" value="{{$deduction->amount}}"></td>
                            <td>
                                <i class="fa fa-edit index_edit_icon mr-2" data-type="deduction" data-action="edit" data-id="{{$deduction->id}}" v-on:click="change_extra_deduction_content"></i>
                                <i class="fa fa-trash index_delete_icon" data-type="deduction" data-action="delete" data-id="{{$deduction->id}}" v-on:click="change_extra_deduction_content"></i>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="project_name">ثبت اضافه کار</label>
                <table id="extra_work_table" class="table table-bordered invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ</th>
                        <th>
                            <i class="fa fa-plus add_icon" v-on:click="create_new_invoice_extra_line"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(component, index) in extra_inline" :key="index" :is="component"></tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="project_name">ثبت کسر کار</label>
                <table id="deduction_work_table" class="table table-bordered invoice_ex_de_table text-center iran_yekan">
                    <thead>
                    <tr>
                        <th>شرح</th>
                        <th>مبلغ</th>
                        <th>
                            <i class="fa fa-plus add_icon" v-on:click="create_new_invoice_deduction_line"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(component, index) in deduction_inline" :key="index" :is="component"></tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group col-12">
                <input type="checkbox" name="final_invoice" value="1" @if($invoice->is_final) checked @endif>
                <label class="col-form-label iran_yekan black_color" for="final_invoice">وضعیت قطعی پیمان</label>
            </div>
            <div class="form-group col-12">
                <input type="hidden" name="invoice_comment_id" value="@if($invoice->comments->isNotEmpty()){{$invoice->comments[0]->id}}@else{{null}}@endif">
                <label class="col-form-label iran_yekan black_color" for="comment">ثبت توضیحات</label>
                <textarea class="form-control new_invoice_comment_box iran_yekan" name="comment">@if($invoice->comments->isNotEmpty()){{$invoice->comments[0]->id}}@else{{null}}@endif</textarea>
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
    </div>
@endsection
