@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش حضور و غیاب</span>
@endsection
@section('content')
    @can('create','Attendances')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Leaves.store")}}" data-type="create" method="post" v-on:submit="submit_form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="staff_id">کارمند</label>
                            <select class="form-control iran_yekan select_picker @error('staff_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="staff_id" name="staff_id">
                                @forelse($users as $user)
                                    <option @if(old("staff_id") == $user->id) selected @endif value="{{$user->id}}">{{$user->name}}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('staff_id')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row border rounded pb-2 mt-2">
                        <div class="col-12 position-relative form_label_container">
                            <h6 class="iran_yekan m-0 text-muted form_label">اطلاعات مرخصی</h6>
                        </div>
                        <div class="form-group col-12 iran_yekan">
                            <table id="contractor_banks" class="table table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">نوع</th>
                                    <th scope="col" class="text-center">تاریخ</th>
                                    <th scope="col" class="text-center">زمان خروج</th>
                                    <th scope="col" class="text-center">زمان ورود</th>
                                    <th scope="col" class="text-center">حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(leave_item, index) in leave_items">
                                    <tr :key="index">
                                        <td>@{{ leave_item.leave_type }}<input type="hidden" name="data[]" :value="JSON.stringify(leave_item)"/></td>
                                        <td>@{{ leave_item.leave_date }}</td>
                                        <td>@{{ leave_item.departure_time }}</td>
                                        <td>@{{ leave_item.arrival_time }}</td>
                                        <td><i class="fa fa-trash button_icon" style="cursor: pointer" v-on:click="leave_items.splice(index, 1)"></i></td>
                                    </tr>
                                </template>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" data-value="presence" class="btn btn-outline-primary iran_yekan mr-2 submit_button" data-type="daily" data-modal_name="daily_leave" v-on:click="show_modal">
                                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                            <i v-show="button_not_loading" class="fa fa-calendar button_icon"></i>
                                            <span v-show="button_not_loading">روزانه</span>
                                        </button>
                                        <button type="button" data-value="absence" class="btn btn-outline-info iran_yekan mr-2 submit_button" data-type="hourly" data-modal_name="daily_leave" v-on:click="show_modal">
                                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                            <i v-show="button_not_loading" class="fa fa-clock button_icon"></i>
                                            <span v-show="button_not_loading">ساعتی</span>
                                        </button>
                                        <button type="submit" data-value="absence" class="btn btn-outline-success iran_yekan mr-2 submit_button" v-on:click="add_value_to_input">
                                            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                            <i v-show="button_not_loading" class="fa fa-save button_icon"></i>
                                            <span v-show="button_not_loading">ثبت</span>
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام کارمند" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">کارمند</th>
                <th scope="col">نوع</th>
                <th scope="col">سال</th>
                <th scope="col">ماه</th>
                <th scope="col">روز</th>
                <th scope="col">ساعت</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                @can('edit','Leaves')
                    <th scope="col">ویرایش</th>
                @endcan
                @can('destroy','Leaves')
                    <th scope="col">حذف</th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @forelse($daily_leaves as $daily)
                @foreach($daily->days as $day)
                <tr>
                    <td><span>{{$day->id}}</span></td>
                    <td><span>{{$daily->staff->name}}</span></td>
                    <td><span>{{"روزانه"}}</span></td>
                    <td><span>{{$day->year}}</span></td>
                    <td><span>{{$day->month}}</span></td>
                    <td><span>{{$day->day}}</span></td>
                    <td><span>{{"-"}}</span></td>
                    <td><span>{{$daily->user->name}}</span></td>
                    <td><span>{{verta($daily->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($daily->updated_at)->format("Y/n/d")}}</span></td>
                    @can('edit','Leaves')
                        <td>
                            <a class="index_action" href="{{route("Leaves.edit",$daily->id."@daily")}}"><i class="fa fa-pen index_edit_icon"></i></a>
                        </td>
                    @endcan
                    @can('destroy','Leaves')
                        <td>
                            <form id="delete_form_{{$daily->id}}" class="d-inline-block" action="{{route("Leaves.destroy",$daily->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="type" value="daily">
                                <button class="index_form_submit_button" form="delete_form_{{$daily->id}}" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        </td>
                    @endcan
                </tr>
                @endforeach
            @empty
            @endforelse
            @forelse($hourly_leaves as $hourly)
                <tr>
                    <td><span>{{$hourly->id}}</span></td>
                    <td><span>{{$hourly->staff->name}}</span></td>
                    <td><span>{{"ساعتی"}}</span></td>
                    <td><span>{{$hourly->year}}</span></td>
                    <td><span>{{$hourly->month}}</span></td>
                    <td><span>{{$hourly->day}}</span></td>
                    <td><span>{{$hourly->departure." تا ".$hourly->arrival}}</span></td>
                    <td><span>{{$hourly->user->name}}</span></td>
                    <td><span>{{verta($hourly->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($hourly->updated_at)->format("Y/n/d")}}</span></td>
                    @can('edit','Leaves')
                        <td>
                            <a class="index_action" href="{{route("Leaves.edit",$hourly->id."@hourly")}}"><i class="fa fa-pen index_edit_icon"></i></a>
                        </td>
                    @endcan
                    @can('destroy','Leaves')
                        <td>
                            <form id="delete_form_{{$hourly->id}}" class="d-inline-block" action="{{route("Leaves.destroy",$hourly->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="type" value="hourly">
                                <button class="index_form_submit_button" form="delete_form_{{$hourly->id}}" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                            </form>
                        </td>
                    @endcan
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="daily_leave" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" v-text="'مرخصی ' + leave_type"></h6>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters iran_yekan">
                        <div class="col-12">
                            <label class="col-form-label iran_yekan black_color" for="leave_date">
                                تاریخ
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control masked text-center" v-model="leave_date" data-mask="0000/00/00">
                            <small class="iran_yekan">فرمت صحیح به صورت مثلا 1401/02/09</small>
                            <input type="hidden" v-model="leave_type">
                            <input type="hidden" v-model="leave_type_hidden">
                        </div>
                        <div class="col-12" v-show="leave_type_show">
                            <label class="col-form-label iran_yekan black_color" for="leave_date">
                                شروع مرخصی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="time" v-model="departure_time" class="form-control text-center h-30">
                        </div>
                        <div class="col-12" v-show="leave_type_show">
                            <label class="col-form-label iran_yekan black_color" for="leave_date">
                                پایان مرخصی
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="time" v-model="arrival_time" class="form-control text-center h-30">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="add_leave_information">
                        <i class="fa fa-plus fa-1_4x"></i>
                        اضافه به لیست
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times fa-1_4x"></i>
                        بستن
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
