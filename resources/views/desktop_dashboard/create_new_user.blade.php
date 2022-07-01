@extends('desktop_dashboard.d_dashboard')
@section('styles')
    <link href="{{asset("/css/persianDatepicker-default.css")}}" rel="stylesheet">
    <link href="{{asset("/css/bootstrap-select.css")}}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/persianDatepicker.min.js")}}" defer></script>
    <script type="text/javascript" src="{{asset("/js/bootstrap-select.min.js")}}" defer></script>
@endsection
@section('page_title')
    ایجاد کاربر جدید
@endsection
@section('content')
    <form id="create_form" action="{{route("Users.store")}}" method="post" data-type="create" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">نام و نام خانوادگی</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="username">نام کاربری</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('username') is-invalid @enderror" id="username" name="username" value="{{old("username")}}">
                @error('username')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="password">کلمه عبور</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password" value="{{old("password")}}">
                @error('password')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="password_confirmation">تکرار کلمه عبور</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" value="{{old("password-confirm")}}">
                @error('password_confirmation')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="role_id">
                    سمت
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('role_id') is-invalid @enderror" data-size="5" data-live-search="true" id="role_id" name="role_id">
                    @forelse($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @empty
                        <option value="0">سمتی وجود ندارد</option>
                    @endforelse
                </select>
                @error('role_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="email">پست الکترونیکی</label>
                <input type="text" class="form-control iran_yekan text-center @error('email') is-invalid @enderror" id="email" name="email" value="{{old("email")}}">
                @error('email')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="project_id">
                    پروژه های مجاز به دسترسی
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('role_id') is-invalid @enderror" title="انتخاب کنید" multiple data-actions-box="true" data-size="8" data-live-search="true" id="project_id" name="project_id[]">
                    @forelse($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @empty
                        <option>پروژه ای وجود ندارد</option>
                    @endforelse
                </select>
                @error('role_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="mobile">تلفن همراه</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{old("mobile")}}">
                @error('mobile')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="sign">اسکن امضاء</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('sign') is-invalid @enderror" v-on:change="file_browser_change" id="sign" name="sign" accept=".jpg,.png,.bmp,.jpeg">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('sign')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="live_data_adding_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="live_data_adding_modal_title">@{{live_data_adding_modal_title}}</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">@{{live_data_adding_label}}</label>
                            <input type="text" class="form-control" v-model="live_data_adding_value">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" v-on:click="live_data_adding_submit">ارسال و ذخیره</button>
                </div>
            </div>
        </div>
    </div>
@endsection
