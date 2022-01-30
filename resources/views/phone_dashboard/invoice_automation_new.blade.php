@extends('phone_dashboard.p_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    <span class="laleh external_page_title_text text-muted text-center">صورت وضعیت های جدید</span>
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه، پیمان و پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1,2,3]'>
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
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoice_automations as $invoice_automation)
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
                    <td>
                        <a class="index_action" href="{{route("InvoiceAutomation.details",$invoice_automation->invoice->id)}}"><i class="fa fa-cog index_edit_icon"></i></a>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
