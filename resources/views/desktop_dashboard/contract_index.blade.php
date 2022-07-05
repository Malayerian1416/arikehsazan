@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    تعریف و ویرایش پیمان
@endsection
@section('content')
    @can('create','Contracts')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
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
                            <input type="text" readonly dir="ltr" style="direction: ltr" class="form-control iran_yekan text-center @error('contract_row') is-invalid @enderror" id="contract_row" name="contract_row" value="{{$contract_row}}">
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
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پیمان، پروژه و یا پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1","2","3"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">پروژه</th>
                <th scope="col">پیمانکار</th>
                <th scope="col">مبلغ پیمان(ریال)</th>
                <th scope="col">وضعیت</th>
                <th scope="col">قرارداد</th>
                <th scope="col">کاربر</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">وضعیت</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contracts as $contract)
                <tr>
                    <td><span>{{$contract->id}}</span></td>
                    <td><span>{{$contract->name}}</span></td>
                    <td><span>{{$contract->project->name}}</span></td>
                    <td><span>{{$contract->contractor->name}}</span></td>
                    <td><span>{{number_format($contract->amount)}}</span></td>
                    @if($contract->is_active == 1)
                        <td><span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span></td>
                    @elseif($contract->is_active == 0)
                        <td><span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span></td>
                    @endif
                    @if(in_array($contract->id,$docs))
                        <td><a href="{{route("contract_doc_download",$contract->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده قرارداد"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{$contract->user->name}}</span></td>
                    <td><span>{{verta($contract->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($contract->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" role="button" href="{{route("Contracts.edit",$contract->id)}}">
                            <i class="fa fa-pen index_edit_icon"></i>
                        </a>
                    </td>
                    <td>
                        <form id="active_chg_form_{{$contract->id}}" class="d-inline-block" action="{{route("contract_change_activation",$contract->id)}}" method="post">
                            @csrf
                            <button class="index_form_submit_button" type="submit"><i class="fa fa-power-off index_active_chg_icon"></i></button>
                        </form>
                    </td>
                    <td>
                        <form id="delete_form_{{$contract->id}}" class="d-inline-block" action="{{route("Contracts.destroy",$contract->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">
                        <span>اطلاعاتی یافت نشد</span>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('page_footer')
    <div class="form-row text-center p-3 d-flex flex-row justify-content-center">
        <button type="button" class="btn btn-outline-info iran_yekan mr-2 search_button">
            <i class="fa fa-filter button_icon"></i>
            <span>فیلتر</span>
        </button>
        <a href="{{route("Contracts.index")}}">
            <button type="button" class="btn btn-outline-danger mr-2 iran_yekan">
                <i class="fa fa-times button_icon"></i>
                <span>فیلتر</span>
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route("Contracts.index")}}" method="get">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="live_data_adding_modal_title">جستجوی پیشرفته</h6>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="text" hidden name="search_request" value="search_request">
                                <input class="align_middle" type="radio" checked id="project_search" name="search_option[]" value="project">
                                <label class="col-form-label iran_yekan black_color" for="project_search">پروژه</label>
                                <select class="form-control mb-0 iran_yekan select_picker" title="انتخاب کنید" data-size="5" data-live-search="true" name="project_id">
                                    @forelse($projects as $project)
                                        <option value="{{$project->id}}">{{$project->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <input class="align_middle" type="radio" id="contractor_search" name="search_option[]" value="contractor">
                                <label class="col-form-label iran_yekan black_color" for="contractor_search">پیمانکار</label>
                                <select class="form-control mb-0 iran_yekan select_picker" title="انتخاب کنید" data-size="5" data-live-search="true" name="contractor_id">
                                    @forelse($contractors as $contractor)
                                        <option value="{{$contractor->id}}">{{$contractor->name}}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label iran_yekan black_color" for="date_sort">تاریخ ثبت</label>
                                <select class="form-control mb-0 iran_yekan" name="date_sort" id="date_sort">
                                    <option value="ASC">صعودی</option>
                                    <option value="DESC">نزولی</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary">جستجو</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
