@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    مشاهده لیست صورت وضعیت های ارسال شده
@endsection
@section('content')
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" list="contractor_data" placeholder="جستجو در جدول با نام پروژه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
            <datalist id="contractor_data" class="iran_yekan">
{{--                @forelse($projects as $project)--}}
{{--                    <option value="{{$project->name}}">{{$project->name}}</option>--}}
{{--                @empty--}}
{{--                @endforelse--}}
            </datalist>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1"]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">مبلغ پیمان(ریال)</th>
                <th scope="col">تاریخ عقد قرارداد</th>
                <th scope="col">تاریخ شروع پروژه</th>
                <th scope="col">تاریخ پایان پروژه</th>
                <th scope="col">قرارداد</th>
                <th scope="col">تاریخ ثبت</th>
                <th scope="col">تاریخ ویرایش</th>
                <th scope="col">ویرایش</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td><span>{{$project->id}}</span></td>
                    <td><span>{{$project->name}}</span></td>
                    <td><span>{{number_format($project->contract_amount)}}</span></td>
                    <td><span >{{$project->date_of_contract}}</span></td>
                    <td><span>{{$project->project_start_date}}</span></td>
                    <td><span>{{$project->project_completion_date}}</span></td>
                    @if(in_array($project->id,$docs))
                        <td><a href="{{route("project_doc_download",$project->id)}}" data-toggle="tooltip" data-placement="top" title="دانلود فایل فشرده قرارداد"><i class="fa fa-download green_color" style="font-size: 1.3rem"></i></a></td>
                    @else
                        <td><i class="fa fa-times red_color" style="font-size: 1.3rem"></i></td>
                    @endif
                    <td><span>{{verta($project->created_date)->format("Y/n/d")}}</span></td>
                    <td><span>{{verta($project->updated_date)->format("Y/n/d")}}</span></td>
                    <td>
                        <a class="index_action" href="{{route("Projects.edit",$project->id)}}"><i class="fa fa-pen index_edit_icon"></i></a>
                    </td>
                    <td>
                        <form id="delete_form_{{$project->id}}" class="d-inline-block" action="{{route("Projects.destroy",$project->id)}}" method="post" v-on:submit="submit_delete_form">
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
