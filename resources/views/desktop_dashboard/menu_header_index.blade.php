@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    ایجاد، مشاهده و ویرایش سرفصل منو
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام و یا برچسب" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1,2]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">برچسب</th>
                <th scope="col">آیکون</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($menu_headers as $menu_header)
                <tr>
                    <td><span>{{$menu_header->id}}</span></td>
                    <td><span>{{$menu_header->name}}</span></td>
                    <td><span>{{$menu_header->slug}}</span></td>
                    <td><i class="{{$menu_header->icon->name}}"></i></td>
                    <td><span>{{verta($menu_header->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($menu_header->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("MenuHeaders.edit",$menu_header->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$menu_header->id}}" class="d-inline-block" action="{{route("MenuHeaders.destroy",$menu_header->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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
        <a href="{{route("MenuHeaders.create")}}">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-plus button_icon"></i>
                <span>ایجاد سرفصل منوی جدید</span>
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
