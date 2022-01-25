@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده لیست حساب های بانکی و ویرایش
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام بانک" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">حساب</th>
                <th scope="col">کارت</th>
                <th scope="col">شبا</th>
                <th scope="col">موجودی</th>
                <th scope="col">چک</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ایجاد</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">صورت حساب</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bank_accounts as $bank_account)
                <tr>
                    <td><span>{{$bank_account->id}}</span></td>
                    <td><span>{{$bank_account->name}}</span></td>
                    <td><span>{{$bank_account->account_number}}</span></td>
                    <td><span>{{$bank_account->card_number}}</span></td>
                    <td><span>{{$bank_account->sheba_number}}</span></td>
                    <td><span>{{number_format(array_sum($bank_account->docs->pluck('amount')->toArray()))}}</span></td>
                    <td>
                        @if($bank_account->checks->isNotEmpty())
                            <span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span>
                        @else
                            <span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span>
                        @endif
                    </td>
                    <td><span>{{$bank_account->user->name}}</span></td>
                    <td><span>{{verta($bank_account->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($bank_account->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("BankAccounts.edit",$bank_account->id)}}"><i class="fa fa-file-invoice-dollar index_edit_icon"></i></a>
                    </td>
                    <td>
                        <a class="index_action" href="{{route("BankAccounts.edit",$bank_account->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$bank_account->id}}" class="d-inline-block" action="{{route("BankAccounts.destroy",$bank_account->id)}}" method="post" v-on:submit="submit_delete_form">
                            @csrf
                            @method('delete')
                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
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
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
