@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    تعریف و ویراش پروژه ها
@endsection
@section('content')
    @can('create','Projects')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
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
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">مبلغ پیمان(ریال)</th>
                <th scope="col">تاریخ عقد قرارداد</th>
                <th scope="col">تاریخ شروع پروژه</th>
                <th scope="col">تاریخ پایان پروژه</th>
                <th scope="col">قرارداد</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                @can('edit','Projects')
                    <th scope="col">ویرایش</th>
                @endcan
                @can('destroy','Projects')
                    <th scope="col">حذف</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td><span>{{$project->id}}</span></td>
                    <td><span>{{$project->name}}</span></td>
                    <td><span>{{number_format($project->contract_amount)}}</span></td>
                    <td><span >{{$project->date_of_contract}}</span></td>
                    <td><span>{{$project->project_start_date}}</span></td>
                    <td><span>{{$project->project_completion_date}}</span></td>
                    @if(in_array($project->id,$docs))
                        <td><a href="{{route("project_doc_download",$project->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده قرارداد"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{verta($project->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($project->updated_at)->format("Y/n/d")}}</span></td>
                    @can('edit','Projects')
                        <td>
                            <a class="index_action" href="{{route("Projects.edit",$project->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                        </td>
                    @endcan
                    @can('destroy','Projects')
                        <td>
                            <form id="delete_form_{{$project->id}}" class="d-inline-block" action="{{route("Projects.destroy",$project->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <button class="index_form_submit_button" form="delete_form_{{$project->id}}" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        </td>
                    @endcan
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
