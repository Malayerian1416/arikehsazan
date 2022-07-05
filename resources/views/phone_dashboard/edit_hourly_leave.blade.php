@extends('phone_dashboard.p_dashboard')

@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش درخواست مرخصی ساعتی"}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("HourlyLeaves.update",$leave->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row pb-2">
            <div class="alert alert-warning w-100 iran_yekan" role="alert">
                <p class="text-justify m-0">
                    <i class="fa fa-info-circle fa-1_6x mr-2"></i>
                    در صورت وارد نکردن زمان شروع و پایان، پس از ارسال درخواست، زمان شروع مرخصی مطابق با زمان فعلی و زمان ثبت ورود مجدد شما به موقعیت مورد نظر، به عنوان زمان پایان مرخصی لحاظ میگردد.
                </p>
            </div>
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="reason">
                    لطفا علت درخواست مرخصی را به طور مختصر شرح دهید
                    <strong class="red_color">*</strong>
                </label>
                <textarea type="text" class="form-control iran_yekan text-center @error('reason') is-invalid @enderror" id="reason" name="reason">{{$leave->reason}}</textarea>
                @error('reason')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="location_id">
                    <i id="location_loading" class="fa fa-spinner fa-spin" style="display: none"></i>
                    <span id="location_text">موقعیت فعلی</span>
                </label>
                <input type="text" name="location_name" id="location_name" readonly class="form-control iran_yekan" value="{{$leave->location ? $leave->location->name : "نامشخص"}}">
                <input type="hidden" id="location_id" name="location_id">
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="departure">
                    شروع مرخصی
                </label>
                <input type="time" name="departure" value="{{$leave->departure}}" class="form-control text-center @error('departure') is-invalid @enderror" style="height: 30px">
                @error('departure')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="arrival">
                    پایان مرخصی
                </label>
                <input type="time" name="arrival" value="{{$leave->arrival ? $leave->arrival : ""}}" class="form-control text-center @error('arrival') is-invalid @enderror" style="height: 30px">
                @error('arrival')
                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-12 col-lg-4 col-xl-3">
                <label class="col-form-label iran_yekan black_color" for="leave_docs">اسکن مدارک</label>
                <input type="file" hidden class="form-control iran_yekan text-center @error('leave_docs') is-invalid @enderror" v-on:change="file_browser_change" multiple id="agreement_sample" name="leave_docs[]" accept=".pdf,.doc,.docx,.jpg,.png,.bmp,.jpeg,.xls,.xlsx,.txt">
                <input type="text" class="form-control iran_yekan text-center file_selector_box" v-on:click="popup_file_browser" id="file_browser_box" readonly value="فایلی انتخاب نشده است">
                @error('leave_docs')
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
        <a href="{{route("HourlyLeaves.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
            </button>
        </a>
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
                        <a class="print_anchor" download href="{{"/storage/hourly_leave_docs/$doc"}}">
                            <h5 class="iran_yekan d-inline-block">
                                {{$doc}}
                            </h5>
                            <form id="delete_form_{{$loop->index}}" class="d-inline-block" action="{{route("DeleteHourlyDoc.delete_doc")}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="doc" value="{{$doc}}">
                                <input type="hidden" name="type" value="hourly_leave_docs">
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
