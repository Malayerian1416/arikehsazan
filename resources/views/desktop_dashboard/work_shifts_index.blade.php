@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    تعریف و ویرایش نوبت کاری
@endsection
@section('content')
    @can('create','Contractors')
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <h6 class="pb-3">
                    <i class="fa fa-plus-square fa-2x hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف جدید</span>
                </h6>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("WorkShifts.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                    @csrf
                    <div class="form-row pb-2">
                        <div class="form-group col-md-12 col-lg-4 col-xl-12">
                            <label class="col-form-label iran_yekan black_color" for="name">
                                عنوان
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                            @error('name')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-6">
                            <label class="col-form-label iran_yekan black_color" for="father_name">ساعت شروع کار</label>
                            <input type="time" class="form-control iran_yekan text-center @error('arrival') is-invalid @enderror" id="arrival" name="arrival" value="{{old("arrival")}}">
                            @error('arrival')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-6">
                            <label class="col-form-label iran_yekan black_color" for="father_name">ساعت پایان کار</label>
                            <input type="time" class="form-control iran_yekan text-center @error('departure') is-invalid @enderror" id="departure" name="departure" value="{{old("departure")}}">
                            @error('departure')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-12 text-center pt-3">
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
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام نوبت کاری" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[2]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">ساعت شروع</th>
                <th scope="col">ساعت پایان</th>
                <th scope="col">مدت</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($shifts as $shift)
                <tr>
                    <td><span>{{$shift->id}}</span></td>
                    <td><span>{{$shift->name}}</span></td>
                    <td><span>{{$shift->arrival}}</span></td>
                    <td><span>{{$shift->departure}}</span></td>
                    <td><span>{{$shift->duration}}</span></td>
                    <td><span>{{$shift->user->name}}</span></td>
                    <td><span>{{verta($shift->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($shift->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("WorkShifts.edit",$shift->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$shift->id}}" class="d-inline-block" action="{{route("WorkShifts.destroy",$shift->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
@section('page_footer')
    <div class="form-row text-center p-3 d-flex flex-row justify-content-center">
        <a href="{{route("idle")}}">
        <button type="button" class="btn btn-outline-light iran_yekan">
            <i class="fa fa-backspace button_icon"></i>
            <span>بستن</span>
        </button>
        </a>
    </div>
@endsection
