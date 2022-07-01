@extends('desktop_dashboard.d_dashboard')

@section('page_title')
    {{"ویرایش درخواست مرخصی روزانه"}}
@endsection
@section('content')
    <form id="update_form" action="{{route("Leaves.update",$leave->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input name="leave_type" type="hidden" value="daily">
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="staff_id">
                    پرسنل
                    <strong class="red_color">*</strong>
                </label>
                <select class="form-control iran_yekan select_picker @error('staff_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="staff_id" name="staff_id">
                    @forelse($users as $user)
                        <option @if($leave->daily_leave->staff_id == $user->id) selected @endif value="{{$user->id}}">{{$user->name}}</option>
                    @empty
                    @endforelse
                </select>
                @error('staff_id')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-3 col-xl-2">
                <label class="col-form-label iran_yekan black_color" for="leave_date">
                    تاریخ
                    <strong class="red_color">*</strong>
                </label>
                <input type="text" name="leave_date" class="form-control text-center iran_yekan persian_date @error('leave_date') is-invalid @enderror" value="{{"$leave->year/$leave->month/$leave->day"}}">
                <small class="iran_yekan">فرمت صحیح به صورت مثلا 1401/02/09</small>
                @error('leave_date')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="reason">
                    لطفا علت درخواست مرخصی را به طور مختصر شرح دهید
                    <strong class="red_color">*</strong>
                </label>
                <textarea type="text" class="form-control iran_yekan text-center @error('reason') is-invalid @enderror" id="reason" name="reason">{{$leave->daily_leave->reason}}</textarea>
                @error('reason')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            @if($docs)
                <div class="form-group col-12">
                    <button type="button" class="btn btn-outline-info iran_yekan" data-modal_name="docs_modal" v-on:click="show_modal">
                        <i class="fa fa-paperclip fa-1_4x"></i>
                        مدارک ارسال شده
                    </button>
                </div>
            @endif
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="leave_docs">اسکن مدارک</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('leave_docs') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="leave_docs[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('leave_docs')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endsection
@section('page_footer')
    <div class="form-row m-0 d-flex flex-row justify-content-center">
        <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan submit_button mr-2">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin ml-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ویرایش</span>
        </button>
        <a href="{{route("Leaves.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
        <button type="button" class="btn btn-outline-light iran_yekan">
            <i class="fa fa-backspace button_icon"></i>
            <span>خروج</span>
        </button>
    </div>
@endsection
<div class="modal fade iran_yekan" id="docs_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{"مدارک ارسال شده"}}</h6>
            </div>
            <div class="modal-body">
                @if($docs)
                    @forelse($docs as $doc)
                        <a class="print_anchor" download href="{{"/storage/daily_leave_docs/$doc"}}">
                            <h5 class="iran_yekan d-inline-block">
                                {{$doc}}
                            </h5>
                            <form id="delete_form_{{$loop->index}}" class="d-inline-block" action="{{route("DeleteDailyDoc.delete_doc")}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="doc" value="{{$doc}}">
                                <input type="hidden" name="type" value="daily_leave_docs">
                                <button class="index_form_submit_button" form="delete_form_{{$loop->index}}" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        </a>
                    @empty
                    @endforelse
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
