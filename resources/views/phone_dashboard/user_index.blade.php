@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش کارمندان</span>
@endsection
@section('content')
    @if(auth()->user()->is_admin)
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف کارمند جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any())) active @endif">
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
                            <label class="col-form-label iran_yekan black_color" for="father_name">نام پدر</label>
                            <input type="text" class="form-control iran_yekan text-center @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old("father_name")}}">
                            @error('father_name')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="birth_date">تاریخ تولد</label>
                            <input type="text" readonly class="form-control persian_date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{old("birth_date")}}">
                            @error('birth_date')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="national_code">
                                کد ملی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked @error('national_code') is-invalid @enderror" data-mask="0000000000" id="national_code" name="national_code" value="{{old("national_code")}}">
                            @error('national_code')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="identify_number">شماره شناسنامه</label>
                            <input type="text" class="form-control iran_yekan text-center masked @error('identify_number') is-invalid @enderror" data-mask="0000000000" id="identify_number" name="identify_number" value="{{old("identify_number")}}">
                            @error('identify_number')
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
                            <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box1" readonly value="فایلی انتخاب نشده است">
                            @error('sign')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن مدارک</label>
                            <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                            <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box2" readonly value="فایلی انتخاب نشده است">
                            @error('agreement_sample')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-8 col-xl-6">
                            <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                            <input type="text" class="form-control iran_yekan text-center @error('address') is-invalid @enderror" id="address" name="address" value="{{old("address")}}">
                            @error('address')
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
    @endif
    @can('create','Users')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
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
                            <label class="col-form-label iran_yekan black_color" for="father_name">نام پدر</label>
                            <input type="text" class="form-control iran_yekan text-center @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{old("father_name")}}">
                            @error('father_name')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="birth_date">تاریخ تولد</label>
                            <input type="text" readonly class="form-control persian_date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{old("birth_date")}}">
                            @error('birth_date')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="national_code">
                                کد ملی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center masked @error('national_code') is-invalid @enderror" data-mask="0000000000" id="national_code" name="national_code" value="{{old("national_code")}}">
                            @error('national_code')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="identify_number">شماره شناسنامه</label>
                            <input type="text" class="form-control iran_yekan text-center masked @error('identify_number') is-invalid @enderror" data-mask="0000000000" id="identify_number" name="identify_number" value="{{old("identify_number")}}">
                            @error('identify_number')
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
                            <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box1" readonly value="فایلی انتخاب نشده است">
                            @error('sign')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="agreement_sample">اسکن مدارک</label>
                            <input type="file" hidden class="form-control iran_yekan text-center @error('agreement_sample') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="agreement_sample[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                            <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box2" readonly value="فایلی انتخاب نشده است">
                            @error('agreement_sample')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-8 col-xl-6">
                            <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                            <input type="text" class="form-control iran_yekan text-center @error('address') is-invalid @enderror" id="address" name="address" value="{{old("address")}}">
                            @error('address')
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
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پروژه و یا سمت" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1,2]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">سمت</th>
                <th scope="col">پروژه های مجاز</th>
                <th scope="col">توسط</th>
                <th scope="col">وضعیت حساب</th>
                <th scope="col">مدارک</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">ویرایش</th>
                <th scope="col">فعال/غیرفعال</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><span>{{$user->id}}</span></td>
                    <td><span>{{$user->name}}</span></td>
                    <td><span>{{$user->role->name}}</span></td>
                    <td>
                        <select class="form-control">
                            @forelse($user->permitted_project as $project)
                                <option>{{$project->name}}</option>
                            @empty
                                <option>پروژه ای ندارد</option>
                            @endforelse
                        </select>
                    </td>
                    <td><span>{{$user->user->name}}</span></td>
                    @if($user->is_active == 1)
                        <td><span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span></td>
                    @elseif($user->is_active == 0)
                        <td><span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span></td>
                    @endif
                    @if(in_array($user->id,$docs))
                        <td><a href="{{route("user_doc_download",$user->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده مدارک"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{verta($user->created_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Users.edit",$user->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="activation_form_{{$user->id}}" action="{{route("Users.activation",$user->id)}}" method="post" data-type="active" v-on:submit="submit_form" data-status="{{$user->is_active}}">
                            @csrf
                            @method('put')
                            @if($user->is_active == 0)
                                <button class="index_form_submit_button" type="submit"><i class="fa fa-unlock index_active_chg_icon" data-toggle="tooltip" title="فعال سازی"></i></button>
                            @elseif($user->is_active == 1)
                                <button class="index_form_submit_button" type="submit"><i class="fa fa-lock index_active_chg_icon" data-toggle="tooltip" title="غیرفعال سازی"></i></button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <form id="delete_form_{{$user->id}}" action="{{route("Users.destroy",$user->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
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
