@extends('desktop_dashboard.d_dashboard')
@section('scripts')
    @if($contract->category)
        <script>
            let searches = @json($contract_branches->where("id",$contract->category->branch->id)->first()->categories->flatten()->toArray());
            let related_data_select = {{$contract->category->id}};
        </script>
    @endif
@endsection
@section('page_title')
    {{"ویرایش پیمان ".$contract->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("Contracts.update",$contract->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('project_id') is-invalid @enderror" data-live-search="true" id="project_id" name="project_id">
                    @forelse($projects as $project)
                        <option @if($contract->project->id == $project->id) selected @endif value="{{$project->id}}">{{$project->name}}</option>
                    @empty
                        <option value="0">پروژه ای وجود ندارد</option>
                    @endforelse
                </select>
                @error('project_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_branch_id">رشته پیمان</label>
                <select class="form-control iran_yekan text-center select_picker @error('contract_branch_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="contract_branch_id" name="contract_branch_id" v-on:change="related_data_search" data-type="contract_category" data-related_id="{{$contract->category->id}}" ref="parent_select">
                    @forelse($contract_branches as $branch)
                        <option @if($branch->id == $contract->category->branch->id) selected @endif value="{{$branch->id}}">{{$branch->branch}}</option>
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
                    <option v-for='search in searches' v-bind:value="search.id" v-bind:key="search.id">@{{ search.category }}</option>
                </select>
                @error('contract_category_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contractor_id">پیمانکار</label>
                <strong class="red_color">*</strong>
                <select class="form-control iran_yekan text-center select_picker @error('contractor_id') is-invalid @enderror" data-live-search="true" id="contractor_id" name="contractor_id">
                    @forelse($contractors as $contractor)
                        <option @if($contract->contractor->id == $contractor->id) selected @endif value="{{$contractor->id}}">{{$contractor->name}}</option>
                    @empty
                        <option value="0">پیمانکاری وجود ندارد</option>
                    @endforelse
                </select>
                @error('contractor_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">عنوان پیمان</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$contract->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_row">شماره قرارداد</label>
                <input type="text" class="form-control iran_yekan text-center @error('contract_row') is-invalid @enderror" id="contract_row" name="contract_row" value="{{$contract->contract_row}}">
                @error('contract_row')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="amount">
                    مبلغ واحد پیمان(ریال)
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center number_format @error('amount') is-invalid @enderror" data-a-sep="," id="amount" name="amount" value="{{$contract->amount}}">
                @error('amount')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="unit_id">
                    واحد شمارش
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('unit_id') is-invalid @enderror" data-size="5" data-type="new_unit" data-live-search="true" id="unit_id" name="unit_id">
                    @forelse($units as $unit)
                        <option @if($contract->unit->id == $unit->id) selected @endif value="{{$unit->id}}">{{$unit->name}}</option>
                    @empty
                        <option value="0">واحد شمارشی وجود ندارد</option>
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
                <input type="text" readonly class="form-control persian_date @error('date_of_contract') is-invalid @enderror" id="date_of_contract" name="date_of_contract" value="{{verta($contract->date_of_contract)->format("Y/n/j")}}">
                @error('date_of_contract')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_start_date">
                    تاریخ شروع پیمان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('contract_start_date') is-invalid @enderror" id="contract_start_date" name="contract_start_date" value="{{verta($contract->contract_start_date)->format("Y/n/j")}}">
                @error('contract_start_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="contract_completion_date">
                    تاریخ پایان پیمان
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('contract_completion_date') is-invalid @enderror" id="contract_completion_date" name="contract_completion_date" value="{{verta($contract->contract_completion_date)->format("Y/n/j")}}">
                @error('contract_completion_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن قرارداد</label>
                @if($docs)
                    <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="{{count($docs) . " فایل آپلود شده است"}}">
                @else
                    <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @endif
                <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                @error('agreement_sample')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
    @if($docs)
        <div class="row no-gutters mt-3 doc_container">
            <div class="col-12">
                <h5 class="iran_yekan border-bottom mb-5 pb-2 doc_expand">
                    <i class="fa fa-arrow-alt-circle-left doc_expand_icon"></i>
                    مشاهده مدارک
                </h5>
            </div>
            @forelse($docs as $doc)
                <div class="col-md-12 col-lg-4 col-xl-3 border d-flex flex-row justify-content-center align-items-center doc">
                    <div class="doc_cover">
                        <div>
                            <a download href="{{"/storage/contracts_doc/$doc"}}" title="دانلود">
                                <i class="fa fa-download white_color border p-2 doc_icon"></i>
                            </a>
                        </div>
                        <form id="delete_form" action="{{route("DestroyContractDoc")}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <input type="text" hidden value="{{$contract->id}}" name="id">
                            <input type="text" hidden value="{{$doc}}" name="filename">
                            <button class="icon_button" type="submit"><i class="fa fa-trash white_color border p-2 doc_icon"></i></button>
                        </form>
                    </div>
                    <a download href="{{"/storage/contracts_doc/$doc"}}" title="دانلود">
                        <img src="{{"/storage/contracts_doc/$doc"}}" class="img-fluid" style="max-height: 200px">
                    </a>
                </div>
            @empty
                <h5 class="iran_yekan">تصویری وجود ندارد</h5>
            @endforelse
        </div>
    @endif
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin ml-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("Contracts.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
        <button type="button" class="btn btn-outline-light iran_yekan">
            <i class="fa fa-backspace button_icon"></i>
            <span>خروج</span>
        </button>
    </div>
@endsection
