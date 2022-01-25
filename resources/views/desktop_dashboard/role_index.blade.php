@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده لیست سمت ها و ویرایش
@endsection
@section('content')
    <div class="table-responsive pt-4">
        <table class="table table-hover iran_yekan index_table">
            <thead>
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">وضعیت</th>
                <th scope="col">توسط</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($roles as $role)
                <tr>
                    <td><span>{{$role->id}}</span></td>
                    <td><span>{{$role->name}}</span></td>
                    @if($role->is_active == 1)
                        <td><span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span></td>
                    @elseif($role->is_active == 0)
                        <td><span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span></td>
                    @endif
                    <td><span>{{$role->user->name}}</span></td>
                    <td><span>{{verta($role->created_date)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($role->updated_date)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Roles.edit",$role->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$role->id}}" class="d-inline-block" action="{{route("Roles.destroy",$role->id)}}" method="post" v-on:submit="submit_delete_form">
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
