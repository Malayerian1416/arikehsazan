@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
    <script>
        const check_already_information = @json($bank_account->checks->toArray());
    </script>
@endsection
@section('page_title')
    {{"ویرایش مشخصات حساب بانکی - ".$bank_account->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("BankAccounts.update",$bank_account->id)}}" method="post" data-type="update" v-on:submit="submit_form">
        @csrf
        @method('put')
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
                        <option @if($bank->name == $bank_account->name) selected @endif value="{{$bank->name}}">{{$bank->name}}</option>
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
                <input type="text" class="form-control iran_yekan text-center @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{$bank_account->branch}}">
                @error('branch')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="branch_code">
                    کد شعبه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center masked @error('branch_code') is-invalid @enderror" data-mask="0000000000" id="branch_code" name="branch_code" value="{{$bank_account->branch_code}}">
                @error('branch_code')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="phone">
                    شماره تماس
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('phone') is-invalid @enderror masked" data-mask="000-00000000" id="phone" name="phone" value="{{$bank_account->phone}}">
                @error('phone')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="account_number">
                    شماره حساب
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('account_number') is-invalid @enderror masked" data-mask="000000000000000000000000000000" id="account_number" name="account_number" value="{{$bank_account->account_number}}">
                @error('account_number')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="card_number">
                    شماره کارت
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('card_number') is-invalid @enderror masked" data-mask="0000-0000-0000-0000" id="card_number" name="card_number" value="{{$bank_account->card_number}}">
                @error('card_number')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="sheba_number">
                    شماره شبا
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('sheba_number') is-invalid @enderror masked" data-mask="IR00-0000-0000-0000-0000-0000-00" id="sheba_number" name="sheba_number" value="{{$bank_account->sheba_number}}">
                @error('sheba_number')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="balance">
                    موجودی اولیه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('balance') is-invalid @enderror number_format" id="balance" name="balance" value="{{$bank_account->docs->first()->amount}}">
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
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("BankAccounts.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
        <a href="{{route("idle")}}" class="index_action">
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
