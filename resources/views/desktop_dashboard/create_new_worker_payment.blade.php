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
    ایجاد پرداختی کارگری جدید
@endsection
@section('content')
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
