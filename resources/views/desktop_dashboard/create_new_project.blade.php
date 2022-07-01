@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}" defer></script>
@endsection
@section('page_title')
    ایجاد پروژه جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Projects.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">شماره قرارداد</label>
                <input type="text" class="form-control iran_yekan text-center @error('contract_row') is-invalid @enderror" id="contract_row" name="contract_row" value="{{old("contract_row")}}">
                @error('contract_row')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">دستگاه نظارت</label>
                <input type="text" class="form-control iran_yekan text-center @error('control_system') is-invalid @enderror" id="control_system" name="control_system" value="{{old("control_system")}}">
                @error('control_system')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">کارفرما</label>
                <input type="text" class="form-control iran_yekan text-center @error('executive_system') is-invalid @enderror" id="executive_system" name="executive_system" value="{{old("executive_system")}}">
                @error('executive_system')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    مبلغ پیمان(ریال)
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center number_format @error('contract_amount') is-invalid @enderror" id="contract_amount" name="contract_amount" value="{{old("contract_amount")}}">
                @error('contract_amount')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ عقد قرارداد
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control persian_date @error('date_of_contract') is-invalid @enderror" id="date_of_contract" name="date_of_contract" value="{{old("date_of_contract")}}">
                @error('date_of_contract')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ شروع پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('project_start_date') is-invalid @enderror" id="project_start_date" name="project_start_date" value="{{old("project_start_date")}}">
                @error('project_start_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ پایان پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('project_completion_date') is-invalid @enderror" id="project_completion_date" name="project_completion_date" value="{{old("project_completion_date")}}">
                @error('project_completion_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن قرارداد</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('agreement_sample')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <label class="col-form-label iran_yekan black_color" for="project_name">آدرس پروژه</label>
                <input type="text" class="form-control iran_yekan text-center @error('project_address') is-invalid @enderror" id="project_address" name="project_address" value="{{old("project_address")}}">
                @error('project_address')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ذخیره</span>
        </button>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
