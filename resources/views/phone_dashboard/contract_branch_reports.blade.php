@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">گزارشات رشته پیمان</span>
@endsection
@section('content')
    <form action="{{route("Reports.contract_branch_reports_make")}}" method="post">
        @csrf
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">مشخصات گزارش</h6>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه
                </label>
                <select class="form-control select_picker iran_yekan @error('project_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="project_id" name="project_id">
                    @forelse($projects as $project)
                        <option @if($project->id == old("project_id")) selected @elseif(isset($project_id) && $project->id == $project_id) selected @endif value="{{$project->id}}">{{$project->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('project_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_branch_id">
                    رشته پیمان
                </label>
                <select class="form-control select_picker iran_yekan @error('contract_branch_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="contract_branch_id" name="contract_branch_id">
                    @forelse($contract_branches as $contract_branch)
                        <option @if($contract_branch->id == old("contract_branch_id")) selected @elseif(isset($contract_branch_id) && $contract_branch->id == $contract_branch_id) selected @endif value="{{$contract_branch->id}}">{{$contract_branch->branch}}</option>
                    @empty
                    @endforelse
                </select>
                @error('contract_branch_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color">از تاریخ</label>
                <input type="text" class="form-control persian_date @error('from_date') is-invalid @enderror" name="from_date" autocomplete="off" value="@if(isset($from_date)) {{$from_date}} @else {{old("from_date")}} @endif">
                @error('from_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message." (مثلا : 1400/02/01)" }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="project_name">تا تاریخ</label>
                <input type="text" class="form-control persian_date @error('to_date') is-invalid @enderror" name="to_date" autocomplete="off" value="@if(isset($to_date)) {{$to_date}} @else {{old("to_date")}} @endif">
                @error('to_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message." (مثلا : 1400/02/01)" }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2 align-self-end">
                <button class="btn btn-outline-secondary iran_yekan @error('project_id') mb-4 @enderror @error('from_date') mb-4 @enderror @error('to_date') mb-4 @enderror" style="height: 30px">
                    <i class="fa fa-search"></i>
                    جستجو و نمایش
                </button>
            </div>
        </div>
    </form>
    <div class="form-row border rounded pb-2 mt-5">
        <div class="col-12 position-relative form_label_container">
            <h6 class="iran_yekan m-0 text-muted form_label">نتیجه گزارش</h6>
        </div>

        <div class="col-12 mb-3 mt-3">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با تمامی عناوین" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
        <div class="table-responsive col-12">
            <table class="table table-striped iran_yekan index_table" id="main_table" data-filter='[1,2,3,4]'>
                <thead class="thead-bg-color">
                <tr>
                    <th scope="col">ردیف</th>
                    <th scope="col">سرفصل</th>
                    <th scope="col">پیمان</th>
                    <th scope="col">پیمانکار</th>
                    <th scope="col">وضعیت</th>
                    <th scope="col">تاریخ</th>
                    <th scope="col">کارکرد</th>
                    <th scope="col">جمع کل</th>
                    <th scope="col">پرداختی</th>
                    <th scope="col">باقیمانده</th>
                </tr>
                </thead>
                @if(isset($results))
                    <tbody>
                    @php
                        $total_max = 0;
                        $total_payed = 0;
                    @endphp
                    @forelse($results as $result)
                        <tr v-on:click="invoice_details" data-invoice_id="{{$result->id}}">
                            <td><span>{{$loop->iteration}}</span></td>
                            <td><span>{{$result->contract->category->category}}</span></td>
                            <td><span>{{$result->contract->name}}</span></td>
                            <td><span>{{$result->contract->contractor->name}}</span></td>
                            <td><span>{{$result->number}}</span></td>
                            <td><span>{{verta($result->created_at)->format("Y/n/d")}}</span></td>
                            <td>
                                <span>
                                    @if($result->automation_amounts->where("is_main",1)->isNotEmpty())
                                        {{$result->automation_amounts->where("is_main",1)->first()->quantity}}
                                    @else
                                        {{"مشخص نشده"}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span>
                                    @if($result->automation_amounts->where("is_main",1)->isNotEmpty())
                                        @php
                                            $total = 0;
                                            $quantity = $result->automation_amounts->where("is_main",1)->first()->quantity;
                                            $amount = $result->automation_amounts->where("is_main",1)->first()->amount;
                                            $extras = $result->extras->sum("amount");
                                            $deductions = $result->deductions->sum("amount");
                                            $total = ($quantity * $amount) + ($extras - $deductions);
                                            $total_max += $total;
                                        @endphp
                                        {{number_format($total)}}
                                    @else
                                        {{"مشخص نشده"}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span>
                                    @if($result->payments->isNotEmpty())
                                        @php($total_payed += $result->payments->sum("amount_payed"))
                                        {{number_format($result->payments->sum("amount_payed"))}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($result->automation_amounts->where("is_main",1)->isNotEmpty())
                                    @if($result->payments->isNotEmpty())
                                        <span class="@if($total - $result->payments->sum("amount_payed") > 0) red_color @endif @if($total - $result->payments->sum("amount_payed") < 0) green_color @endif">{{number_format(abs($total - $result->payments->sum("amount_payed")))}}</span>
                                    @else
                                        <span class="red_color">{{number_format(abs($total))}}</span>
                                    @endif
                                @else
                                    مشخص نشده
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10"><span>اطلاعاتی یافت نشد</span></td></tr>
                    @endforelse
                    </tbody>
                @endif
            </table>
        </div>
        @if(isset($results))
            <div class="mb-3 mt-3" style="position: sticky;bottom: 0;right:2px">
                <table class="table table-dark table-bordered font-size-header iran_yekan">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="2">جمع مقادیر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span>جمع کل هزینه ها</span></td>
                        <td><span>{{number_format($total_max)." ریال"}}</span></td>
                    </tr>
                    <tr>
                        <td><span>جمع کل پرداختی ها</span></td>
                        <td><span>{{number_format($total_payed)." ریال"}}</span></td>
                    </tr>
                    <tr>
                        <td>
                            @if($total_max - $total_payed > 0)
                                <span>جمع کل بدهکاری</span>
                            @elseif($total_max - $total_payed < 0)
                                <span>جمع کل بستانکاری</span>
                            @else
                                <span>باقیمانده کل</span>
                            @endif
                        </td>
                        <td>
                            <span class="@if($total_max - $total_payed > 0) red_slow_color @elseif($total_max - $total_payed < 0) green_slow_color @endif">{{number_format(abs($total_max - $total_payed))." ریال"}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="invoice_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">جزئیات صورت وضعیت</h6>
                </div>
                <div class="modal-body">
                    <div class=table-responsive">
                        <table class="table text-center invoice_detail_modal">
                            <thead id="invoice_detail_head">
                            <tr id="roles_titles">

                            </tr>
                            <tr id="roles_head" class="bg-dark white_color"></tr>
                            </thead>
                            <tbody>
                            <tr id="amounts_body"></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-12 col-md-6 pr-2">
                            <table class="table table-striped invoice_detail_modal_ex">
                                <thead>
                                <tr class="bg-dark white_color"><th colspan="2">اضافه کار</th></tr>
                                <tr class="bg-success white_color">
                                    <th>شرح</th>
                                    <th>مبلغ</th>
                                </tr>
                                </thead>
                                <tbody id="extras_body"></tbody>
                            </table>
                            <table class="table table-striped invoice_detail_modal_ex">
                                <thead>
                                <tr class="bg-dark white_color"><th colspan="2">کسر کار</th></tr>
                                <tr class="bg-danger white_color">
                                    <th>شرح</th>
                                    <th>مبلغ</th>
                                </tr>
                                </thead>
                                <tbody id="deductions_body"></tbody>
                            </table>
                        </div>
                        <div class="col-12 col-md-6">
                            <table class="table table-striped invoice_detail_modal_ex text-center">
                                <thead>
                                <tr class="bg-dark white_color"><th colspan="3">مجموع قابل پرداخت</th></tr>
                                <tr>
                                    <th>سمت</th>
                                    <th>جمع کل(ریال)</th>
                                    <th>پیشنهاد پرداخت(ریال)</th>
                                </tr>
                                </thead>
                                <tbody id="totals_body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary m-auto" data-dismiss="modal" v-on:click="clear_invoice_details">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
