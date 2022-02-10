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
    {{"ویرایش کاربر ".$user->name}}
@endsection
@section('content')
    <form id="update_form" action="{{route("Users.update",$user->id)}}" method="post" v-on:submit="submit_update_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="name">نام و نام خانوادگی</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{$user->name}}">
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="username">نام کاربری</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center @error('username') is-invalid @enderror" id="username" name="username" value="{{$user->username}}">
                @error('username')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="password">کلمه عبور</label>
                <input type="text" class="form-control iran_yekan text-center @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                @error('password')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="password_confirmation">تکرار کلمه عبور</label>
                <input type="text" class="form-control iran_yekan text-center @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
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
                        <option @if($user->role->id == $role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
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
                <input type="text" class="form-control iran_yekan text-center @error('email') is-invalid @enderror" id="email" name="email" value="{{$user->email}}">
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
                        <option @if(in_array($project->id,array_column($user->permitted_project->toArray(),"id"))) selected @endif value="{{$project->id}}">{{$project->name}}</option>
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
                <input type="text" class="form-control iran_yekan text-center @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{$user->mobile}}">
                @error('mobile')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="sign">اسکن امضاء</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('sign') is-invalid @enderror" v-on:change="file_browser_change" id="sign" name="sign" accept=".jpg,.png,.bmp,.jpeg">
                @if($user->sign == null)
                    <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @else
                    <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="امضاء آپلود شده است">
                @endif
                @error('sign')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("Users.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
