@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش کاربر ".$user->name}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("Users.update",$user->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
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
                <label class="col-form-label iran_yekan black_color" for="gender">جنسیت</label>
                <strong class="red_color">*</strong>
                <select class="form-control select_picker iran_yekan" title="انتخاب کنید" name="gender" id="gender">
                    <option @if($user->gender == "مرد") selected @endif value="مرد">مرد</option>
                    <option @if($user->gender == "زن") selected @endif value="زن">زن</option>
                </select>
                @error('name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="father_name">نام پدر</label>
                <input type="text" class="form-control iran_yekan text-center @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{$user->father_name}}">
                @error('father_name')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="birth_date">تاریخ تولد</label>
                <input type="text" readonly class="form-control persian_date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{$user->birth_date}}">
                @error('birth_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="national_code">
                    کد ملی
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" class="form-control iran_yekan text-center masked @error('national_code') is-invalid @enderror" data-mask="0000000000" id="national_code" name="national_code" value="{{$user->national_code}}">
                @error('national_code')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="identify_number">شماره شناسنامه</label>
                <input type="text" class="form-control iran_yekan text-center masked @error('identify_number') is-invalid @enderror" data-mask="0000000000" id="identify_number" name="identify_number" value="{{$user->identify_number}}">
                @error('identify_number')
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
                <label class="col-form-label iran_yekan black_color" for="email">پست الکترونیکی</label>
                <input type="text" class="form-control iran_yekan text-center @error('email') is-invalid @enderror" id="email" name="email" value="{{$user->email}}">
                @error('email')
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
            <div class="form-group col-md-12 col-lg-8 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                <input type="text" class="form-control iran_yekan text-center @error('address') is-invalid @enderror" id="address" name="address" value="{{$user->address}}">
                @error('address')
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
                    @endforelse
                </select>
                @error('role_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="work_shift_id">
                    نوبت کاری
                </label>
                <select class="form-control iran_yekan text-center select_picker @error('work_shift_id') is-invalid @enderror" data-size="8" data-live-search="true" id="work_shift_id" name="work_shift_id">
                    <option value="">هیچکدام</option>
                    @forelse($shifts as $shift)
                        <option @if($user->work_shift && $shift->id == $user->work_shift->id) selected @endif value="{{$shift->id}}">{{$shift->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('work_shift_id')
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
                    @endforelse
                </select>
                @error('role_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="daily_wage">دستمزد روزانه</label>
                <strong class="red_color">*</strong>
                <input type="text" class="form-control iran_yekan text-center number_format @error('daily_wage') is-invalid @enderror" id="daily_wage" name="daily_wage" value="{{$user->daily_wage}}">
                @error('daily_wage')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="overtime_rate">ضریب اضافه کاری</label>
                <strong class="red_color">*</strong>
                <input type="number" step=".01" class="form-control iran_yekan text-center @error('overtime_rate') is-invalid @enderror" id="overtime_rate" name="overtime_rate" value="{{$user->overtime_rate}}">
                @error('overtime_rate')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="delay_rate">ضریب تاخیر</label>
                <strong class="red_color">*</strong>
                <input type="number" step=".01" class="form-control iran_yekan text-center @error('delay_rate') is-invalid @enderror" id="delay_rate" name="delay_rate" value="{{$user->delay_rate}}">
                @error('delay_rate')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="acceleration_rate">ضریب تعجیل</label>
                <strong class="red_color">*</strong>
                <input type="number" step=".01" class="form-control iran_yekan text-center @error('acceleration_rate') is-invalid @enderror" id="acceleration_rate" name="acceleration_rate" value="{{$user->acceleration_rate}}">
                @error('acceleration_rate')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="absence_rate">ضریب غیبت</label>
                <strong class="red_color">*</strong>
                <input type="number" step=".01" class="form-control iran_yekan text-center @error('absence_rate') is-invalid @enderror" id="absence_rate" name="absence_rate" value="{{$user->absence_rate}}">
                @error('absence_rate')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="mission_rate">ضریب ماموریت</label>
                <strong class="red_color">*</strong>
                <input type="number" step=".01" class="form-control iran_yekan text-center @error('mission_rate') is-invalid @enderror" id="mission_rate" name="mission_rate" value="{{$user->mission_rate}}">
                @error('mission_rate')
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
                            <a class="print_anchor" download href="{{"/storage/users_doc/$doc"}}" title="دانلود">
                                <i class="fa fa-download white_color border p-2 doc_icon"></i>
                            </a>
                        </div>
                        <form id="delete_form" action="{{route("DestroyUserDoc")}}" method="post" data-type="delete" v-on:submit="submit_form">
                            @csrf
                            @method('delete')
                            <input type="text" hidden value="{{$user->id}}" name="id">
                            <input type="text" hidden value="{{$doc}}" name="filename">
                            <button class="icon_button" type="submit"><i class="fa fa-trash white_color border p-2 doc_icon"></i></button>
                        </form>
                    </div>
                    <a class="print_anchor" download href="{{"/storage/users_doc/$doc"}}" title="دانلود">
                        <img src="{{"/storage/users_doc/$doc"}}" class="img-fluid" style="max-height: 200px">
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

    </div>
@endsection
