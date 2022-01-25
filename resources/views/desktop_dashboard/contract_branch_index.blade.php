@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    ایجاد، مشاهده و ویرایش رشته های پیمان
@endsection
@section('content')
    <div class="table-responsive pt-4">
        <table class="table table-hover iran_yekan index_table">
            <thead>
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام شاخه</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contract_branches as $branch)
                <tr>
                    <td><span>{{$branch->id}}</span></td>
                    <td><span>{{$branch->branch}}</span></td>
                    <td><span>{{$branch->user->name}}</span></td>
                    <td><span>{{verta($branch->created_date)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($branch->updated_date)->format("Y/n/d")}}</span></td>
                    <td>
                        <i class="fa fa-pen index_edit_icon" data-route="{{route("ContractBranches.update",$branch->id)}}" data-value="{{$branch->branch}}" v-on:click="static_data_edit_modal"></i>
                    </td>
                    <td>
                        <form id="delete_form_{{$branch->id}}" class="d-inline-block" action="{{route("ContractBranches.destroy",$branch->id)}}" method="post" v-on:submit="submit_delete_form">
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
            <span>ایجاد رشته پیمان جدید</span>
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
    <div class="modal fade iran_yekan" id="data_adding_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="live_data_adding_modal_title">ایجاد رشته پیمان جدید</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">عنوان رشته</label>
                            <form id="create_form" action="{{route("ContractBranches.store")}}" method="post" v-on:submit="submit_create_form">
                                @csrf
                                <input type="text" class="form-control" id="branch_add" name="branch">
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
                    <h6 class="modal-title">ویرایش رشته پیمان</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label class="form-check-label p-0">عنوان رشته</label>
                            <form id="update_form" action="" method="post" v-on:submit="submit_update_form">
                                @csrf
                                @method('put')
                                <input type="text" class="form-control" id="branch_edit" name="branch" v-model="edit_input_data">
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
