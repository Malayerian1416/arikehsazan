@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}" defer></script>
@endsection
@section('page_title')
    {{"ویرایش پروژه ".$project->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("Projects.update",$project->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">
                    نام پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$project->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">شماره قرارداد</label>
                <input type="text" class="form-control iran_yekan text-center @error('contract_row') is-invalid @enderror" id="contract_row" name="contract_row" value="{{$project->contract_row}}">
                @error('contract_row')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">دستگاه نظارت</label>
                <input type="text" class="form-control iran_yekan text-center @error('control_system') is-invalid @enderror" id="control_system" name="control_system" value="{{$project->control_system}}">
                @error('control_system')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">کارفرما</label>
                <input type="text" class="form-control iran_yekan text-center @error('executive_system') is-invalid @enderror" id="executive_system" name="executive_system" value="{{$project->executive_system}}">
                @error('executive_system')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    مبلغ پیمان(ریال)
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center number_format @error('contract_amount') is-invalid @enderror" data-a-sep="," id="contract_amount" name="contract_amount" value="{{$project->contract_amount}}">
                @error('contract_amount')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ عقد قرارداد
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control persian_date @error('date_of_contract') is-invalid @enderror" id="date_of_contract" name="date_of_contract" value="{{$project->date_of_contract}}">
                @error('date_of_contract')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ شروع پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('project_start_date') is-invalid @enderror" id="project_start_date" name="project_start_date" value="{{$project->project_start_date}}">
                @error('project_start_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_name">
                    تاریخ پایان پروژه
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" readonly class="form-control iran_yekan persian_date @error('project_completion_date') is-invalid @enderror" id="project_completion_date" name="project_completion_date" value="{{$project->project_completion_date}}">
                @error('project_completion_date')
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
            <div class="form-group col-md-12 col-xl-4">
                <label class="col-form-label iran_yekan black_color" for="project_name">آدرس پروژه</label>
                <input type="text" class="form-control iran_yekan text-center @error('project_address') is-invalid @enderror" id="project_address" name="project_address" value="{{$project->project_address}}">
                @error('project_address')
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
                            <a download href="{{"/storage/projects_doc/$doc"}}" title="دانلود">
                                <i class="fa fa-download white_color border p-2 doc_icon"></i>
                            </a>
                        </div>
                        <form id="delete_form" action="{{route("DestroyProjectDoc")}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <input type="text" hidden value="{{$project->id}}" name="id">
                            <input type="text" hidden value="{{$doc}}" name="filename">
                            <button class="icon_button" type="submit"><i class="fa fa-trash white_color border p-2 doc_icon"></i></button>
                        </form>
                    </div>
                    <a download href="{{"/storage/projects_doc/$doc"}}" title="دانلود">
                        <img src="{{"/storage/projects_doc/$doc"}}" class="img-fluid" style="max-height: 200px">
                    </a>
                </div>
            @empty
                <h5 class="iran_yekan">تصویری وجود ندارد</h5>
            @endforelse
        </div>
    @endif
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("Projects.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>بستن</span>
            </button>
        </a>
    </div>
@endsection
