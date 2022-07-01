@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده و ویرایش اتوماسیون پرداختی های کارگری ایجاد شده
@endsection
@section('content')
    @can('create','WorkerPayments')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <h6 class="pb-3">
                    <i class="fa fa-plus-square fa-2x hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف وضعیت جدید</span>
                </h6>
            </div>
            <div class="col-12 hide_section @if($errors->any())) active @endif">
                <form id="create_form" action="{{route("WorkerPayments.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 col-lg-4">
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
                        <div class="form-group col-md-12 col-lg-4">
                            <label class="col-form-label iran_yekan black_color" for="contractor_id">کارگر</label>
                            <select class="form-control iran_yekan text-center select_picker @error('contractor_id') is-invalid @enderror" title="انتخاب کنید" data-size="5" data-live-search="true" id="contractor_id" name="contractor_id">
                                @forelse($workers as $worker)
                                    <option value="{{$worker->id}}">{{$worker->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('contractor_id')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4">
                            <label class="col-form-label iran_yekan black_color" for="amount">
                                مبلغ قابل پرداخت(ریال)
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center number_format @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{old("amount")}}">
                            @error('amount')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="description">
                                توضیحات
                            </label>
                            <textarea class="form-control iran_yekan" id="description" name="description"></textarea>
                            @error('description')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-12 text-center">
                        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
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
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه و یا کارگر" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1","2"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام پروژه</th>
                <th scope="col">نام کارگر</th>
                <th scope="col">توسط</th>
                <th scope="col">مبلغ</th>
                <th scope="col">توضیحات</th>
                <th scope="col">موقعیت</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                @can('edit','WorkerPayments')
                    <th scope="col">ویرایش</th>
                @endcan
                @can('destroy','WorkerPayments')
                    <th scope="col">حذف</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @forelse($worker_automations as $worker_automation)
                <tr>
                    <td><span>{{$worker_automation->id}}</span></td>
                    <td><span>{{$worker_automation->project->name}}</span></td>
                    <td><span>{{$worker_automation->contractor->name}}</span></td>
                    <td><span >{{$worker_automation->user->name}}</span></td>
                    <td><span>{{number_format($worker_automation->amount)}}</span></td>
                    <td><span>{{$worker_automation->description}}</span></td>
                    <td>
                        <span>
                            @if($worker_automation->current_role_id == 0)
                                پرداخت شده
                            @else
                                {{\App\Models\Role::query()->findOrFail($worker_automation->current_role_id)->name}}
                            @endif
                        </span>
                    </td>
                    <td><span>{{verta($worker_automation->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($worker_automation->updated_at)->format("Y/n/d")}}</span></td>
                    @can('edit','WorkerPayments')
                        <td>
                            @if($worker_automation->previous_role_id == \App\Models\InvoiceFlow::query()->where("is_starter",1)->first()->role_id)
                                <a href="{{route("WorkerPayments.edit",$worker_automation->id)}}" class="d-inline-block">
                                    <i class="fa fa-edit index_edit_icon"></i>
                                </a>
                            @else
                                <i class="fa fa-times-circle index_delete_icon red_color"></i>
                            @endif
                        </td>
                    @endcan
                    @can('destroy','WorkerPayments')
                        <td>
                            @if($worker_automation->previous_role_id == \App\Models\InvoiceFlow::query()->where("is_starter",1)->first()->role_id)
                                <form id="delete_form_{{$worker_automation->id}}" class="d-inline-block" action="{{route("WorkerPayments.destroy",$worker_automation->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                    @csrf
                                    @method('delete')
                                    <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                                </form>
                            @else
                                <i class="fa fa-times-circle index_delete_icon red_color"></i>
                            @endif
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
