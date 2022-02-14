@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده لیست کاربران سامانه و ویرایش
@endsection
@section('content')
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
                    <td><span>{{verta($user->created_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Users.edit",$user->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="activation_form_{{$user->id}}" action="{{route("Users.activation",$user->id)}}" method="post" v-on:submit="submit_activation_form" data-status="{{$user->is_active}}">
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
                        <form id="delete_form_{{$user->id}}" action="{{route("Users.destroy",$user->id)}}" method="post" v-on:submit="submit_delete_form">
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
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
