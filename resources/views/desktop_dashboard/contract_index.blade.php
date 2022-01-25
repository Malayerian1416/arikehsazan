@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}"></script>
@endsection
@section('page_title')
    مشاهده لیست پیمان ها و ویرایش
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" list="contract_data" placeholder="جستجو در جدول با نام پیمان، پروژه و یا پیمانکار" v-on:input="search_input_filter" aria-describedby="basic-addon3">
            <datalist id="contract_data" class="iran_yekan">
                @forelse($contracts as $contract)
                    <option value="{{$contract->name}}">{{$contract->name}}</option>
                    <option value="{{$contract->contractor->name}}">{{$contract->contractor->name}}</option>
                    <option value="{{$contract->project->name}}">{{$contract->project->name}}</option>
                @empty
                @endforelse
            </datalist>
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
                        <form id="delete_form_{{$contract->id}}" class="d-inline-block" action="{{route("Contracts.destroy",$contract->id)}}" method="post" v-on:submit="submit_delete_form">
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
