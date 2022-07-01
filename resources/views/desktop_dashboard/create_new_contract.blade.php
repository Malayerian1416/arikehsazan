@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    ایجاد پیمان جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Contracts.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('project_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="project_id" name="project_id">
                    @forelse($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @empty
                        <option>پروژه ای وجود ندارد</option>
                    @endforelse
                </select>
                @error('project_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_branch_id">رشته پیمان</label>
                <select class="form-control iran_yekan text-center select_picker @error('contract_branch_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="contract_branch_id" name="contract_branch_id" v-on:change="related_data_search" data-type="contract_category" ref="parent_select">
                    @forelse($contract_branches as $branch)
                        <option value="{{$branch->id}}">{{$branch->branch}}</option>
                    @empty
                    @endforelse
                </select>
                @error('contract_branch_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_category_id">سرفصل پیمان</label>
                <select class="form-control iran_yekan text-center select_picker @error('contract_category_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="contract_category_id" name="contract_category_id" v-model="related_data_select">
                    <option v-for='search in searches' v-bind:value="search.id">@{{ search.category }}</option>
                </select>
                @error('contract_category_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contractor_id">پیمانکار</label>
                <strong class="red_color">*</strong>
                <select class="form-control iran_yekan text-center select_picker @error('contractor_id') is-invalid @enderror" title="انتخاب کنید" data-live-search="true" id="contractor_id" name="contractor_id">
                    @forelse($contractors as $contractor)
                        <option value="{{$contractor->id}}">{{$contractor->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('contractor_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">عنوان پیمان</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_row">شماره قرارداد</label>
                <input type="text" class="form-control iran_yekan text-center @error('contract_row') is-invalid @enderror" id="contract_row" name="contract_row" value="{{old("contract_row")}}">
                @error('contract_row')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="amount">
                    مبلغ واحد پیمان(ریال)
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center number_format @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{old("amount")}}">
                @error('amount')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="unit_id">
                    واحد شمارش
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('unit_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="unit_id" name="unit_id">
                    @forelse($units as $unit)
                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('unit_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="date_of_contract">
                    تاریخ عقد پیمان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control persian_date @error('date_of_contract') is-invalid @enderror" id="date_of_contract" name="date_of_contract" value="{{old("date_of_contract")}}">
                @error('date_of_contract')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_start_date">
                    تاریخ شروع پیمان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('contract_start_date') is-invalid @enderror" id="contract_start_date" name="contract_start_date" value="{{old("contract_start_date")}}">
                @error('contract_start_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_completion_date">
                    تاریخ پایان پیمان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('contract_completion_date') is-invalid @enderror" id="contract_completion_date" name="contract_completion_date" value="{{old("contract_completion_date")}}">
                @error('contract_completion_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن قرارداد</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('agreement_sample')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
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
