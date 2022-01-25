@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}" defer></script>
    <script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
@endsection
@section('page_title')
    ایجاد پیمانکار جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Contractors.store")}}" method="post" v-on:submit="submit_create_form" enctype="multipart/form-data">
        @csrf
        <div class="form-row border rounded pb-2">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات فردی</h6>
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نوع
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center @error('type') is-invalid @enderror" id="type" name="type">
                    <option value="0">پیمانکار</option>
                    <option value="1">کارگر</option>
                </select>
                @error('type')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام و نام خانوادگی
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="father_name">نام پدر</label>
                <input type="text" class="form-control iran_yekan text-center @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old("father_name")}}">
                @error('father_name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="birth_date">تاریخ تولد</label>
                <input type="text" readonly class="form-control persian_date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{old("birth_date")}}">
                @error('birth_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="national_code">
                    کد ملی
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center masked @error('national_code') is-invalid @enderror" data-mask="0000000000" id="national_code" name="national_code" value="{{old("national_code")}}">
                @error('national_code')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="identify_number">شماره شناسنامه</label>
                <input type="text" class="form-control iran_yekan text-center masked @error('identify_number') is-invalid @enderror" data-mask="0000000000" id="identify_number" name="identify_number" value="{{old("identify_number")}}">
                @error('identify_number')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="tel">تلفن(به همراه پیش شماره)</label>
                <input type="text" class="form-control iran_yekan text-center masked @error('tel') is-invalid @enderror" data-mask="000-00000000" id="tel" name="tel" value="{{old("tel")}}">
                @error('tel')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="cellphone">
                    تلفن همراه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center masked @error('cellphone') is-invalid @enderror" data-mask="0000-000-0000" id="cellphone" name="cellphone" value="{{old("cellphone")}}">
                @error('cellphone')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن مدارک</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('agreement_sample')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-8 col-xl-9">
                <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                <input type="text" class="form-control iran_yekan text-center @error('address') is-invalid @enderror" id="address" name="address" value="{{old("address")}}">
                @error('address')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-row border rounded pb-2 mt-4">
            <div class="col-12 position-relative form_label_container">
                <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات بانکی</h6>
            </div>
            <div class="form-group col-12 iran_yekan">
                <table id="contractor_banks" class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="text-center">نام بانک</th>
                        <th scope="col" class="text-center">شماره کارت</th>
                        <th scope="col" class="text-center">شماره حساب</th>
                        <th scope="col" class="text-center">شماره شبا</th>
                        <th scope="col" class="text-center">حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(bank_item, index) in bank_items">
                        <tr :key="index">
                            <td>@{{ bank_item.name }}<input type="hidden" name="bank_names[]" :value="bank_item.name"/></td>
                            <td>@{{ bank_item.card }}<input type="hidden" name="bank_cards[]" :value="bank_item.card"/></td>
                            <td>@{{ bank_item.account }}<input type="hidden" name="bank_accounts[]" :value="bank_item.account"/></td>
                            <td>@{{ bank_item.sheba }}<input type="hidden" name="bank_sheba[]" :value="bank_item.sheba"/></td>
                            <td><i class="fa fa-trash button_icon" style="cursor: pointer" v-on:click="bank_items.splice(index, 1)"></i></td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <button type="button" class="btn btn-outline-secondary iran_yekan form-control" v-on:click="new_bank_information_modal">
                                <i class="fa fa-plus"></i>
                                ایجاد مشخصات جدید حساب بانکی
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
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ذخیره</span>
        </button>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>بستن</span>
            </button>
        </a>
    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="bank_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ثبت اطلاعات بانکی جدید</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="col-form-label iran_yekan black_color" for="bank_name">
                                نام بانک
                                <strong class="red_color">*</strong>
                            </label>
                            <select class="form-control iran_yekan text-center select_picker" title="انتخاب کنید" data-size="5" data-live-search="true" v-model="bank_name">
                                @forelse($banks as $bank)
                                    <option value="{{$bank->name}}">{{$bank->name}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="col-form-label iran_yekan black_color" for="bank_card_number">
                                شماره کارت بانکی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked" data-mask="0000-0000-0000-0000" v-model="bank_card_number">
                        </div>
                        <div class="col-12">
                            <label class="col-form-label iran_yekan black_color" for="bank_account_number">
                                شماره حساب بانکی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000000000000000000000" v-model="bank_account_number">
                        </div>
                        <div class="col-12">
                            <label class="col-form-label iran_yekan black_color" for="bank_sheba_number">
                                شماره شبا بانکی(بدون IR)
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked" data-mask="IR00-0000-0000-0000-0000-0000-00" v-model="bank_sheba_number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="add_bank_information">ثـبـت</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection
