@extends('desktop_dashboard.d_dashboard')
@section('styles')
@endsection
@section('scripts')
@endsection
@section('page_title')
    ایجاد و ویرایش جریان وضعیت
@endsection
@section('content')
    <form id="create_form" action="{{route("InvoiceFlow.store")}}" method="post" data-type="create" v-on:submit="submit_form">
        @csrf
        <select hidden id="final_starter" name="final_starter[]" multiple v-model="final_starter">
            <option v-for="item in final_starter" v-bind:value="item">@{{item}}</option>
        </select>
        <select hidden id="final_inductor" name="final_inductor[]" multiple v-model="final_inductor">
            <option v-for="item in final_inductor" v-bind:value="item">@{{item}}</option>
        </select>
        <input type="hidden" id="final_finisher" name="final_finisher" v-bind:value="final_finisher"/>
        <div class="form-row">
            <div class="form-group col-md-12 col-lg-3">
                <h5 class="iran_yekan">لیست عناوین</h5>
                <div id="origin_list" class="list-group border iran_yekan" style="min-height: 31px">
                    @forelse($roles as $role)
                        <span class="list-group-item" data-id="{{$role->id}}" v-on:click="list_item_select">{{$role->name}}</span>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <h5 class="iran_yekan">ثبت کننده</h5>
                <div id="starter_list" class="list-group border iran_yekan" style="min-height: 31px">

                </div>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <h5 class="iran_yekan">واسطه(به ترتیب اولویت)</h5>
                <div id="inductor_list" class="list-group border iran_yekan" style="min-height: 31px">

                </div>
            </div>
            <div class="form-group col-md-12 col-lg-3">
                <h5 class="iran_yekan">خاتمه دهنده</h5>
                <div id="finisher_list" class="list-group border iran_yekan" style="min-height: 31px">

                </div>
            </div>
            <div class="form-group col-md-12 justify-content-center align-items-center d-flex flex-row">
                <button type="button" class="btn btn-outline-primary iran_yekan mr-2" v-on:click="flow_modal">
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
                <label class="iran_yekan black_color">تعیین کننده نهایی مقادیر وضعیت</label>
                <select class="form-control select_picker iran_yekan" name="is_main" title="انتخاب کنید" data-size="5" data-live-search="true">
                    @forelse($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </form>
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
@section('modal_alerts')
    <div class="modal fade iran_yekan" id="flow_type_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">انتخاب عنوان</h6>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-4 mb-0 text-center">
                            <input type="radio" checked name="flow_type" id="starter">
                            <label for="starter" class="form-check-label p-0">ثبت کننده</label>
                        </div>
                        <div class="form-group col-4 mb-0 text-center">
                            <input type="radio" name="flow_type" id="inductor">
                            <label for="inductor" class="form-check-label p-0">واسطه</label>
                        </div>
                        <div class="form-group col-4 mb-0 text-center">
                            <input type="radio" name="flow_type" id="finisher">
                            <label for="finisher" class="form-check-label p-0">خاتمه دهنده</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" v-on:click="adding_item">ادامه</button>
                </div>
            </div>
        </div>
    </div>
@endsection
