@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset("/js/jquery.mask.js")}}" defer></script>
@endsection
@section('page_title')
    مشاهده لیست پیمانکاران و ویرایش
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام پیمانکار، کد ملی و یا تلفن همراه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[2,3,4]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نوع</th>
                <th scope="col">نام</th>
                <th scope="col">کد ملی</th>
                <th scope="col">تلفن همراه</th>
                <th scope="col">مدارک</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contractors as $contractor)
                <tr>
                    <td><span>{{$contractor->id}}</span></td>
                    <td>
                        <span>
                            @if($contractor->type == 0)
                                پیمانکار
                            @else
                                کارگر
                            @endif
                        </span>
                    </td>
                    <td><span>{{$contractor->name}}</span></td>
                    <td><span>{{$contractor->national_code}}</span></td>
                    <td><span data-mask="0000-000-0000">{{$contractor->cellphone}}</span></td>
                    @if(in_array($contractor->id,$docs))
                        <td><a href="{{route("contractor_doc_download",$contractor->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده مدارک"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{verta($contractor->created_at)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($contractor->updated_at)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Contractors.edit",$contractor->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$contractor->id}}" class="d-inline-block" action="{{route("Contractors.destroy",$contractor->id)}}" method="post" v-on:submit="submit_delete_form">
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
