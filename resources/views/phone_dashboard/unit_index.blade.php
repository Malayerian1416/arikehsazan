@extends('phone_dashboard.p_dashboard')
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">ایجاد، مشاهده و ویرایش واحد های اندازه گیری</span>
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($units as $unit)
                <tr>
                    <td><span>{{$unit->id}}</span></td>
                    <td><span>{{$unit->name}}</span></td>
                    <td><span>{{$unit->user->name}}</span></td>
                    <td><span>{{verta($unit->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($unit->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <i class="fa fa-pen index_edit_icon" data-route="{{route("Units.update",$unit->id)}}" data-value="{{$unit->unit}}" v-on:click="static_data_edit_modal"></i>
                    </td>
                    <td>
                        <form id="delete_form_{{$unit->id}}" class="d-inline-block" action="{{route("Units.destroy",$unit->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
        <button type="button" class="btn btn-outline-info iran_yekan mr-2" v-on:click="static_data_add_modal">
            <i class="fa fa-plus button_icon"></i>
            <span>ایجاد واحد شمارش جدید</span>
        </button>

    </div>
@endsection
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="data_adding_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="live_data_adding_modal_title">ایجاد واحد شمارش جدید</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">عنوان واحد</label>
                            <form id="create_form" action="{{route("Units.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                                @csrf
                                <input type="text" class="form-control" id="name_add" name="name">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="submit" form="create_form" class="btn btn-primary">ارسال و ذخیره</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade iran_yekan" id="data_editing_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ویرایش واحد شمارش</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">عنوان واحد</label>
                            <form id="update_form" action="" method="post" data-type="update" v-on:submit="submit_form">
                                @csrf
                                @method('put')
                                <input type="text" class="form-control" id="name_edit" name="name" v-model="edit_input_data">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="submit" form="update_form" class="btn btn-primary">ارسال و ویرایش</button>
                </div>
            </div>
        </div>
    </div>
@endsection
