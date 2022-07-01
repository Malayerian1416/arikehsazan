@extends("desktop_dashboard.d_dashboard")
@section('styles')
    <style>
        #map { margin: 0; height: 100%; width: 100%; }
    </style>
@endsection
@section('scripts')
    <script>
        let state = @json(json_decode($location->geoJson));
    </script>
    <script type="text/javascript" src="{{asset("/js/map.js")}}" defer></script>
@endsection
@section('page_title')
    ویرایش موقعیت مکانی
@endsection
@section('content')
    <div id="map"></div>
    <div id="location_info" class="card text-white bg-dark">
        <div class="card-header iran_yekan">
            <h6 class="m-0" style="font-weight: 600">
                <i class="fa fa-location-arrow" style="vertical-align: middle;font-size: 1.2rem"></i>
                اطلاعات موقعیت
            </h6>
        </div>
        <div class="card-body">
            <form id="update_form" action="{{route("Locations.update",$location->id)}}" method="post" data-type="update" v-on:submit="submit_form">
                @csrf
                @method("put")
                <input type="hidden" name="geoJson" id="geoJson" value="{{$location->geoJson}}">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label class="white_color col-form-label iran_yekan" for="project_id">
                            پروژه
                        </label>
                        <select class="form-control select_picker iran_yekan @error('project_id') is-invalid @enderror" title="انتخاب کنید" data-size="10" data-live-search="true" id="project_id" name="project_id" v-on:change="related_data_search" data-type="project_contract" ref="parent_select">
                            @forelse($projects as $project)
                                <option @if($project->id == $location->project_id) selected @endif value="{{$project->id}}">{{$project->name}}</option>
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
                        <input class="form-control text-center iran_yekan @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{$location->name}}">
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
            <button type="submit" form="update_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                <span v-show="button_not_loading">ارسال و ویرایش</span>
            </button>
        </div>
    </div>
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <a href="{{route("Locations.index")}}" class="index_action">
            <button type="button" class="btn btn-outline-info iran_yekan mr-2">
                <i class="fa fa-arrow-circle-right button_icon"></i>
                <span>بازگشت به لیست</span>
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
