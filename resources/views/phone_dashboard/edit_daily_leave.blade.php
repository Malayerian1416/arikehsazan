@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">{{"ویرایش درخواست مرخصی روزانه"}}</span>
@endsection
@section('content')
    <form id="update_form" action="{{route("DailyLeaves.update",$leave->id)}}" method="post" data-type="update" v-on:submit="submit_form" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-row">
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
            <div class="form-group col-md-12">
                <label class="col-form-label iran_yekan black_color" for="father_name">
                    انتخاب روز
                    <strong class="red_color">*</strong>
                </label>
                <div class="w-75 m-auto p-2 text-center">
                    <table class="table table-bordered iran_yekan leave_date_table @error('selected_dates') is-invalid @enderror">
                        <thead class="bg-dark">
                        <tr>
                            <th class="text-center">شنبه</th>
                            <th class="text-center">یکشنبه</th>
                            <th class="text-center">دوشنبه</th>
                            <th class="text-center">سه شنبه</th>
                            <th class="text-center">چهارشنبه</th>
                            <th class="text-center">پنجشنبه</th>
                            <th class="text-center">جمعه</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calender as $week)
                            <tr>
                                @foreach($week as $day)
                                    @if($day["day"] != "" && $day["day_off"] == 0)
                                        <td class="calender_day position-relative bg-success" v-on:click="calender_selection">
                                            <div class="cover @if($leave->days->where("year",$day["year"])->where("month",$day["month"])->where("day",$day["day"])->first() != null) active @endif">
                                                <i class="fa fa-check fa-3x white_color"></i>
                                                <input hidden  @if($leave->days->where("year",$day["year"])->where("month",$day["month"])->where("day",$day["day"])->first() != null) checked @endif type="checkbox" name="selected_dates[]" value="{{$day["year"]."/".$day["month"]."/".$day["day"]}}">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <h2>{{$day["day"]}}</h2>
                                                <h4 class="text-light">{{$day["month_name"]}}</h4>
                                                <h6 class="text-light">{{$day["year"]}}</h6>
                                            </div>
                                        </td>
                                    @elseif($day["day"] != "" && $day["day_off"] == 1)
                                        <td class="calender_day day_off position-relative bg-danger">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <h2 class="text-muted-light">{{$day["day"]}}</h2>
                                                <h4 class="text-muted-light">{{$day["month_name"]}}</h4>
                                                <h6 class="text-muted-light">{{$day["year"]}}</h6>
                                            </div>
                                        </td>
                                    @elseif($day["day"] != "" && $day["day_off"] == 2)
                                        <td class="calender_day day_off position-relative bg-warning">
                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                <h2 class="text-muted">{{$day["day"]}}</h2>
                                                <h4 class="text-muted">{{$day["month_name"]}}</h4>
                                                <h6 class="text-muted">{{$day["year"]}}</h6>
                                            </div>
                                        </td>
                                    @else
                                        <td class="bg-secondary"></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @error('selected_dates')
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
        <a href="{{route("DailyLeaves.index")}}" class="index_action">
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
