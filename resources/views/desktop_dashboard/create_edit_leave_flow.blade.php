@extends('desktop_dashboard.d_dashboard')
@section('page_title')
    ایجاد و ویرایش جریان درخواست مرخصی
@endsection
@section('content')
    <form id="create_form" action="{{route("LeaveFlow.store")}}" method="post" data-type="create" v-on:submit="submit_form">
        @csrf
        <select hidden id="final_inductor" name="final_inductor[]" multiple v-model="final_inductor">
            <option v-for="item in final_inductor" v-bind:value="item">@{{item}}</option>
        </select>
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-6">
                <h5 class="iran_yekan">لیست عناوین</h5>
                <div id="origin_list" class="list-group border iran_yekan" style="min-height: 31px">
                    @forelse($roles as $role)
                        <span class="list-group-item" data-id="{{$role->id}}" v-on:click="list_item_select">{{$role->name}}</span>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="form-group col-md-12 col-lg-6">
                <h5 class="iran_yekan">تایید کنندگان(به ترتیب اولویت)</h5>
                <div id="inductor_list" class="list-group border iran_yekan" style="min-height: 31px">

                </div>
            </div>
            <div class="form-group col-md-12 justify-content-center align-items-center d-flex flex-row">
                <button type="button" class="btn btn-outline-primary iran_yekan mr-2" v-on:click="adding_item">
                    <i class="fa fa-plus"></i>
                </button>
                <button type="button" class="btn btn-outline-primary iran_yekan mr-2" v-on:click="remove_item">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-outline-primary iran_yekan mr-2" v-on:click="moving_item_up">
                    <i class="fa fa-arrow-up"></i>
                </button>
                <button type="button" class="btn btn-outline-primary iran_yekan" v-on:click="moving_item_down">
                    <i class="fa fa-arrow-down"></i>
                </button>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <label class="iran_yekan black_color">تعیین کننده نهایی</label>
                <select class="form-control select_picker iran_yekan" name="is_main" title="انتخاب کنید" data-size="5" data-live-search="true">
                    @forelse($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </form>
    <input hidden checked type="radio" name="flow_type" id="inductor">
@endsection
@section('page_footer')
    <div class="form-row pt-3 pb-3 m-0 d-flex flex-row justify-content-end">
        <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
            <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
            <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
            <span v-show="button_not_loading">ارسال و ذخیره</span>
        </button>
        <a href="{{route("idle")}}">
            <button type="button" class="btn btn-outline-light iran_yekan">
                <i class="fa fa-backspace button_icon"></i>
                <span>خروج</span>
            </button>
        </a>
    </div>
@endsection
