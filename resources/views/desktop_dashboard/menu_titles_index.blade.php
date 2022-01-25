@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    ایجاد، مشاهده و ویرایش عناوین اصلی منو
@endsection
@section('content')
    <div class="table-responsive pt-4">
        <table class="table table-hover iran_yekan index_table">
            <thead>
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">گروه</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($menu_titles as $menu_title)
                <tr>
                    <td><span>{{$menu_title->id}}</span></td>
                    <td><span>{{$menu_title->name}}</span></td>
                    <td><span>{{$menu_title->menu_header->name}}</span></td>
                    <td><span>{{verta($menu_title->created_date)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($menu_title->updated_date)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("MenuTitles.edit",$menu_title->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$menu_title->id}}" class="d-inline-block" action="{{route("MenuTitles.destroy",$menu_title->id)}}" method="post" v-on:submit="submit_delete_form">
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
        <a href="{{route("MenuTitles.create")}}">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-plus button_icon"></i>
                <span>ایجاد عنوان اصلی منو</span>
            </button>
        </a>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection