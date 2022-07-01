@extends("phone_dashboard.p_dashboard")
@section('styles')
    <style>
        #map { margin: 0; height: 100%; width: 100%; }
    </style>
@endsection
@section('scripts')
    @if($errors->any())
        <script>
            let state = @json(json_decode(old("geoJson")));
        </script>
    @endif
    <script type="text/javascript" src="{{asset("/js/map.js")}}" defer></script>
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش موقعیت مکانی</span>
@endsection
@section('content')
    <div class="card h-100" style="overflow: hidden;max-height: 100%">
        <div class="card-header iran_yekan">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="create-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="true">تعریف موقعیت جدید</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">مشاهده و ویرایش</a>
                </li>
            </ul>
        </div>
        <div class="card-body" style="overflow: hidden">
            <div class="tab-content" id="myTabContent" style="overflow: hidden">
                <div class="tab-pane fade show active" id="create" role="tabpanel" aria-labelledby="create-tab" style="overflow: hidden;height:calc(100vh - 200px)">
                    <div id="map"></div>
                        <div id="location_info" class="card text-white bg-dark">
                            <div class="card-header iran_yekan">
                                <h6 class="m-0" style="font-weight: 600">
                                    <i class="fa fa-location-arrow" style="vertical-align: middle;font-size: 1.2rem"></i>
                                    اطلاعات موقعیت
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="create_form" action="{{route("Locations.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                                    @csrf
                                    <input type="hidden" name="geoJson" id="geoJson" value="{{old("geoJson")}}">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label class="white_color col-form-label iran_yekan" for="project_id">
                                                پروژه
                                            </label>
                                            <select class="form-control select_picker iran_yekan @error('project_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="project_id" name="project_id" v-on:change="related_data_search" data-type="project_contract" ref="parent_select">
                                                @forelse($projects as $project)
                                                    <option @if($project->id == old("project_id")) selected @endif value="{{$project->id}}">{{$project->name}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('project_id')
                                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="white_color col-form-label iran_yekan" for="name">
                                                نام موقعیت
                                            </label>
                                            <input class="form-control iran_yekan text-center @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{old("name")}}">
                                            @error('name')
                                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                            @enderror
                                            @error('geoJson')
                                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                                    <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                    <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                                    <span v-show="button_not_loading">ارسال و ذخیره</span>
                                </button>
                            </div>
                        </div>
                </div>
                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab" style="overflow: hidden;height:calc(100vh - 200px)">
                    <div class="row pt-1 pb-3">
                        <div class="col-12">
                            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با نام و پروژه" v-on:input="search_input_filter" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='["1","2"]'>
                            <thead class="thead-bg-color">
                            <tr>
                                <th scope="col">شماره</th>
                                <th scope="col">نام موقعیت</th>
                                <th scope="col">پروژه</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">کاربر</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">تاریخ ویرایش</th>
                                <th scope="col">ویرایش</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">حذف</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($locations as $location)
                                <tr>
                                    <td><span>{{$location->id}}</span></td>
                                    <td><span>{{$location->name}}</span></td>
                                    <td><span>{{$location->project->name}}</span></td>
                                    @if($location->is_active == 1)
                                        <td><span><i class="fa fa-check-circle fa-2x green_color fa-1_4x"></i></span></td>
                                    @elseif($location->is_active == 0)
                                        <td><span><i class="fa fa-times-circle fa-2x red_color fa-1_4x"></i></span></td>
                                    @endif
                                    <td><span>{{$location->user->name}}</span></td>
                                    <td><span>{{verta($location->created_at)->format("Y/n/d")}}</span></td>
                                    <td><span>{{verta($location->updated_at)->format("Y/n/d")}}</span></td>
                                    <td>
                                        <a class="index_action" role="button" href="{{route("Locations.edit",$location->id)}}">
                                            <i class="fa fa-pen index_edit_icon"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form id="active_chg_form_{{$location->id}}" class="d-inline-block" action="{{route("location_change_activation",$location->id)}}" method="post">
                                            @csrf
                                            <button class="index_form_submit_button" type="submit"><i class="fa fa-power-off index_active_chg_icon"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form id="delete_form_{{$location->id}}" class="d-inline-block" action="{{route("Locations.destroy",$location->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
                                            @csrf
                                            @method('delete')
                                            <button class="index_form_submit_button" type="submit"><i class="fa fa-trash index_delete_icon"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <span>اطلاعاتی یافت نشد</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
