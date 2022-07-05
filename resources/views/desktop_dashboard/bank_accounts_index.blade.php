@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    مشاهده لیست حساب های بانکی و ویرایش
@endsection
@section('content')
    @can('create','BankAccounts')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->has(["name","branch","branch_code","account_number","card_number","sheba_number","balance"])) active @endif">
                <form id="create_form" action="{{route("BankAccounts.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                    @csrf
                    <div class="form-row border rounded pb-2">
                        <div class="col-12 position-relative form_label_container">
                            <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات حساب</h6>
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="name">
                                نام
                                <strong class="red_color">*</strong>
                            </label>
                            <select class="form-control iran_yekan text-center select_picker @error('name') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="name" name="name">
                                @forelse($banks as $bank)
                                    <option value="{{$bank->name}}">{{$bank->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('name')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="branch">
                                نام شعبه
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{old("branch")}}">
                            @error('branch')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="branch_code">
                                کد شعبه
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked @error('branch_code') is-invalid @enderror" data-mask="0000000000" id="branch_code" name="branch_code" value="{{old("branch_code")}}">
                            @error('branch_code')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="phone">
                                شماره تماس
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('phone') is-invalid @enderror masked" data-mask="000-00000000" id="phone" name="phone" value="{{old("phone")}}">
                            @error('phone')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="account_number">
                                شماره حساب
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('account_number') is-invalid @enderror masked" data-mask="000000000000000000000000000000" id="account_number" name="account_number" value="{{old("account_number")}}">
                            @error('account_number')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="card_number">
                                شماره کارت
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('card_number') is-invalid @enderror masked" data-mask="0000-0000-0000-0000" id="card_number" name="card_number" value="{{old("card_number")}}">
                            @error('card_number')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="sheba_number">
                                شماره شبا
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('sheba_number') is-invalid @enderror masked" data-mask="IR00-0000-0000-0000-0000-0000-00" id="sheba_number" name="sheba_number" value="{{old("sheba_number")}}">
                            @error('sheba_number')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="balance">
                                موجودی اولیه
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('balance') is-invalid @enderror number_format" id="balance" name="balance" value="{{old("balance")}}">
                            @error('balance')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row border rounded pb-2 mt-4">
                        <div class="col-12 position-relative form_label_container">
                            <h6 class="iran_yekan m-0 text-muted form_label">دسته چک</h6>
                        </div>
                        <div class="form-group col-12 iran_yekan">
                            <table id="contractor_banks" class="table table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">سریال</th>
                                    <th scope="col" class="text-center">شماره صیادی</th>
                                    <th scope="col" class="text-center">شماره شروع</th>
                                    <th scope="col" class="text-center">شماره پایان</th>
                                    <th scope="col" class="text-center">حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(check_item, index) in check_items">
                                    <tr :key="index">
                                        <td>@{{ check_item.serial }}<input type="hidden" name="check_serial[]" :value="check_item.serial"/></td>
                                        <td>@{{ check_item.sayyadi }}<input type="hidden" name="check_sayyadi[]" :value="check_item.sayyadi"/></td>
                                        <td>@{{ check_item.start }}<input type="hidden" name="check_start[]" :value="check_item.start"/></td>
                                        <td>@{{ check_item.end }}<input type="hidden" name="check_end[]" :value="check_item.end"/></td>
                                        <td><i class="fa fa-trash button_icon" style="cursor: pointer" v-on:click="check_items.splice(index, 1)"></i></td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-outline-secondary iran_yekan form-control" v-on:click="new_check_information_modal">
                                            <i class="fa fa-plus"></i>
                                            ایجاد مشخصات دسته چک جدید
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-group col-12 text-center">
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
                        <form id="delete_form_{{$bank_account->id}}" class="d-inline-block" action="{{route("BankAccounts.destroy",$bank_account->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="check_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ثبت اطلاعات دسته چک جدید</h6>
                </div>
                <div class="modal-body">
                    <div class="w-100">
                        <label class="col-form-label iran_yekan black_color" for="serial">
                            سریال چک
                            <strong class="red_color">*</strong>
                        </label>
                        <input type="text" class="form-control iran_yekan text-center masked" data-mask="0000000000000000000" v-model="check_serial">
                    </div>
                    <div class="w-100">
                        <label class="col-form-label iran_yekan black_color" for="check_sayyadi">
                            شماره صیادی
                            <strong class="red_color">*</strong>
                        </label>
                        <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000000000000000000000" v-model="check_sayyadi">
                    </div>
                    <div class="w-100">
                        <label class="col-form-label iran_yekan black_color" for="check_start">
                            شماره شروع
                            <strong class="red_color">*</strong>
                        </label>
                        <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000000000000" v-model="check_start" v-on:input="set_check_end">
                    </div>
                    <div class="w-100">
                        <label class="col-form-label iran_yekan black_color" for="check_quantity">
                            چند برگی
                            <strong class="red_color">*</strong>
                        </label>
                        <input type="text" class="form-control iran_yekan text-center masked" data-mask="0000" v-model="check_quantity" v-on:input="set_check_end">
                    </div>
                    <div class="w-100">
                        <label class="col-form-label iran_yekan black_color" for="check_end">
                            شماره پایان
                            <strong class="red_color">*</strong>
                        </label>
                        <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000000000000" v-model="check_end">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="add_check_information">ثـبـت</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
