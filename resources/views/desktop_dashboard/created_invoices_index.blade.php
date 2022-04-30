@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    مشاهده لیست وضعیت های ایجاد شده و ویرایش
@endsection
@section('content')
    @can('create','Invoices')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container pb-3">
                <h6>
                    <i class="fa fa-plus-square fa-2x hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف وضعیت جدید</span>
                </h6>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Invoices.store")}}" method="post" v-on:submit="submit_create_form">
                    @csrf
                    <div class="form-row border rounded pb-2">
                        <div class="col-12 position-relative form_label_container">
                            <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات پیمان</h6>
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="project_id">
                                انتخاب پروژه
                            </label>
                            <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="10" data-live-search="true" id="project_id" name="project_id" v-on:change="related_data_search" data-type="project_contract" ref="parent_select">
                                @forelse($projects as $project)
                                    <option value="{{$project->id}}">{{$project->name}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="contract_id">
                                انتخاب پیمان
                                <i v-show="related_data_search_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            </label>
                            <select class="form-control select_picker iran_yekan" title="انتخاب کنید" data-size="5" data-live-search="true" id="contract_id" name="contract_id" v-model="related_data_select" v-on:change="get_new_invoice_information">
                                <option v-for='search in searches' v-bind:value="search.id">@{{ search.name }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="contract_branch">رشته پیمان</label>
                            <input class="form-control iran_yekan text-center" readonly type="text" v-bind:value="contract_branch">
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="contract_category">سرفصل پیمان</label>
                            <input class="form-control iran_yekan text-center" readonly type="text" v-bind:value="contract_category">
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="contractor_name">پیمانکار</label>
                            <input class="form-control iran_yekan text-center" readonly type="text" v-bind:value="contractor_name">
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-2">
                            <label class="col-form-label iran_yekan black_color" for="invoice_number">شماره وضعیت</label>
                            <input class="form-control iran_yekan text-center" readonly type="text" name="invoice_number" v-bind:value="invoices_count">
                        </div>
                    </div>
                    <div class="form-row border rounded mt-5" id="invoice_information" v-show="new_invoice_frame">
                        <div class="col-12 position-relative form_label_container">
                            <h6 class="iran_yekan m-0 text-muted form_label">ثبت اطلاعات</h6>
                        </div>
                        <div class="form-group col-md-12 col-lg-2">
                            <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount_process">میزان کارکرد</label>
                            <input class="form-control iran_yekan text-center number_format_dec" type="text" name="quantity" v-model="new_invoice_quantity" v-on:input="new_invoice_total_amount_process">
                        </div>
                        <div class="form-group col-md-12 col-lg-2">
                            <label class="col-form-label iran_yekan black_color" for="new_invoice_unit">واحد</label>
                            <input class="form-control iran_yekan text-center" type="text" readonly v-model="new_invoice_unit">
                        </div>
                        <div class="form-group col-md-12 col-lg-2">
                            <label class="col-form-label iran_yekan black_color" for="new_invoice_amount">مبلغ جزء(ریال)</label>
                            <input class="form-control iran_yekan text-center number_format" type="text" readonly name="amount" v-model="new_invoice_amount">
                        </div>
                        <div class="form-group col-md-12 col-lg-2">
                            <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount">مبلغ کل(ریال)</label>
                            <input class="form-control iran_yekan text-center number_format" type="text" readonly v-model="new_invoice_total_amount">
                        </div>
                        <div class="form-group col-md-12 col-lg-4">
                            <label class="col-form-label iran_yekan black_color" for="new_invoice_total_amount">پیشنهاد پرداخت(ریال)</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">%</span>
                                    <input class="form-control iran_yekan text-center" style="height: 30px" step="1" type="number" name="payment_offer_percent" :disabled="payment_offer_disabled" v-model="invoice_payment_offer_percent" v-on:input="invoice_payment_offer_percent_change">
                                </div>
                                <input class="form-control iran_yekan text-center number_format" type="text" name="payment_offer" :disabled="payment_offer_disabled" v-model="invoice_payment_offer" v-on:input="invoice_payment_offer_change">
                            </div>
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
                            <input type="checkbox" name="final_invoice" value="1">
                            <label class="col-form-label iran_yekan black_color" for="project_name">وضعیت قطعی پیمان</label>
                        </div>
                        <div class="form-group col-12">
                            <label class="col-form-label iran_yekan black_color" for="project_name">ثبت توضیحات</label>
                            <textarea class="form-control new_invoice_comment_box iran_yekan" v-model="new_invoice_comment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group col-12 text-center pt-3">
                        <button type="button" class="iran_yekan btn btn-outline-danger mr-2" v-show="new_invoice_frame" v-on:click="clear_invoice_form">
                            <i class="fa fa-eraser button_icon"></i>
                            <span>انصراف از انتخاب</span>
                        </button>
                        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                            <span v-show="button_not_loading">ارسال و ذخیره</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پیمان، پروژه و یا پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1","2","3"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">پروژه</th>
                <th scope="col">پیمان</th>
                <th scope="col">پیمانکار</th>
                <th scope="col">شماره وضعیت</th>
                <th scope="col">موقعیت</th>
                <th scope="col">وضعیت</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td><span>{{$invoice->id}}</span></td>
                    <td><span>{{$invoice->contract->project->name}}</span></td>
                    <td><span>{{$invoice->contract->name}}</span></td>
                    <td><span>{{$invoice->contract->contractor->name}}</span></td>
                    <td><span>{{$invoice->number}}</span></td>
                    @if($invoice->automation->is_finished == 1)
                        <td>تکمیل شده</td>
                    @else
                        @if($invoice->automation->current_role_id != 0)
                            <td>{{\App\Models\Role::query()->findOrFail($invoice->automation->current_role_id)->name}}</td>
                        @else
                            <td>ارجاع شده</td>
                        @endif
                    @endif
                    @if($invoice->payments->isEmpty())
                        <td>در جریان</td>
                    @else
                        <td>پرداخت شده</td>
                    @endif
                    <td><span>{{verta($invoice->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($invoice->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        @if($invoice->automation->previous_role_id == \Illuminate\Support\Facades\Auth::user()->role->id)
                            <a class="index_action" role="button" href="{{route("Invoices.edit",$invoice->id)}}">
                                <i class="fa fa-pen index_edit_icon"></i>
                            </a>
                        @else
                            <i class="fa fa-times red_color"></i>
                        @endif
                    </td>
                    <td>
                        @if($invoice->automation->previous_role_id == \Illuminate\Support\Facades\Auth::user()->role->id)
                            <form id="delete_form_{{$invoice->id}}" class="d-inline-block" action="{{route("Invoices.destroy",$invoice->id)}}" method="post" v-on:submit="submit_delete_form">
                                @csrf
                                @method('delete')
                                <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        @else
                            <i class="fa fa-times red_color"></i>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">
                        <span>اطلاعاتی یافت نشد</span>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('page_footer')
    <div class="form-row text-center p-3 d-flex flex-row justify-content-center">
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>بستن</span>
            </button>
        </a>
    </div>
@endsection
