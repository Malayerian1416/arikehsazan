@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}"></script>
@endsection
@section('page_title')
    مشاهده لیست وضعیت های ایجاد شده و ویرایش
@endsection
@section('content')
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
                        <td>{{\App\Models\Role::query()->findOrFail($invoice->automation->current_role_id)->name}}</td>
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
