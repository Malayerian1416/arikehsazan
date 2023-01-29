@extends('phone_dashboard.p_dashboard')
@section('styles')
@endsection
@section('scripts')
    @if($errors->any())
        <script>
            @foreach(old("bank_names") as $bank)
            @php($array[] = ["name"=>$bank,"card"=>old("bank_cards")[$loop->index],"account"=>old("bank_accounts")[$loop->index],"sheba"=>old("bank_sheba")[$loop->index]])
            @endforeach
            let bank_already_information = @json($array);
        </script>
    @endif
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش پیمانکاران</span>
@endsection
@section('content')
    @can('create','Contractors')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف پیمانکار جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Contractors.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
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
                                <option @if(old("type") == 0) selected @endif value="0">پیمانکار</option>
                                <option @if(old("type") == 1) selected @endif value="1">کارگر</option>
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
                    <div class="form-group col-12 text-center pt-3">
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
            <input type="search" class="form-control iran_yekan text-center" list="contractor_data" placeholder="جستجو در جدول با نام پیمانکار، کد ملی و یا تلفن همراه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
            <datalist id="contractor_data" class="iran_yekan">
                @forelse($contractors as $contractor)
                    <option value="{{$contractor->name}}">{{$contractor->name}}</option>
                @empty
                @endforelse
            </datalist>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered iran_yekan index_table" id="main_table" data-filter='[2,3,4]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نوع</th>
                <th scope="col">نام</th>
                <th scope="col">کد ملی</th>
                <th scope="col">تلفن همراه</th>
                <th scope="col">مدارک</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contractors as $contractor)
                <tr>
                    <td><span>{{$contractor->id}}</span></td>
                    <td>
                        <span>
                            @if($contractor->type == 0)
                                پیمانکار
                            @else
                                کارگر
                            @endif
                        </span>
                    </td>
                    <td><span>{{$contractor->name}}</span></td>
                    <td><span>{{$contractor->national_code}}</span></td>
                    <td><span data-mask="0000-000-0000">{{$contractor->cellphone}}</span></td>
                    @if(in_array($contractor->id,$docs))
                        <td><a href="{{route("contractor_doc_download",$contractor->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده مدارک"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{verta($contractor->created_date)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($contractor->updated_date)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Contractors.edit",$contractor->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$contractor->id}}" class="d-inline-block" action="{{route("Contractors.destroy",$contractor->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="bank_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ثبت اطلاعات بانکی جدید</h6>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters">
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
