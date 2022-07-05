@extends('phone_dashboard.p_dashboard')
@section('scripts')
    @if($errors->has('name') || $errors->has('email'))
        <script>
            let contact = @json($phonebooks->where("id",old("contact_id"))->first());
            let route = @json(route("Phonebook.update",old("contact_id")));
            $(document).ready(function(){
                $("#contact_id").val(contact["id"]);
                $("#e_name").val(contact["name"]);
                $("#e_phone_number_1").val(contact["phone_number_1"]);
                $("#e_phone_number_2").val(contact["phone_number_2"]);
                $("#e_phone_number_3").val(contact["phone_number_3"]);
                $("#e_job_title").val(contact["job_title"]);
                $("#e_email").val(contact["email"]);
                $("#e_address").val(contact["address"]);
                $("#e_note").val(contact["note"]);
                $("#e_update_form").attr("action",route);
                $("#e_contact_information").modal("show");
            });
        </script>
    @endif
@endsection
@section('page_title')
    <span class="iran_yekan external_page_title_text text-muted text-center">تعریف و ویرایش مخاطبین</span>
@endsection
@section('content')
    @error('any')
    <div class="iran_yekan alert alert-danger alert-dismissible fade show" role="alert">
        <h6 style="font-weight: 700">
            <i class="fa fa-times-circle" style="color: #ff0000;min-width: 30px;vertical-align: middle;text-align:center;font-size: 1.5rem"></i>
            در هنگام انجام عملیات، خطای زیر رخ داده است :
        </h6>
        <ul>
            @foreach($errors as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @enderror
    @can("create","Phonebook")
        <div class="row pt-1 pb-3">
            <div class="col-12 hide_section_container">
                <button class="btn btn-outline-success">
                    <i class="fa fa-plus-square fa-1_4x mr-2 hide_section_icon" style="vertical-align: middle"></i>
                    <span class="iran_yekan hide_section_title">تعریف مخاطب جدید</span>
                </button>
            </div>
            <div class="col-12 hide_section @if($errors->any()) active @endif">
                <form id="create_form" action="{{route("Phonebook.store")}}" method="post" data-type="create" v-on:submit="submit_form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="name">
                                نام
                                <strong class="red_color">*</strong>
                            </label>
                            <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="name" name="name" value="{{old("name")}}">
                            @error('name')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="phone_number_1">شماره تماس 1</label>
                            <input type="text" class="form-control iran_yekan text-center @error('phone_number_1') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_1" name="phone_number_1" value="{{old("phone_number_1")}}">
                            @error('phone_number_1')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="phone_number_2">شماره تماس 2</label>
                            <input type="text" class="form-control iran_yekan text-center @error('phone_number_2') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_2" name="phone_number_2" value="{{old("phone_number_2")}}">
                            @error('contract_row')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="phone_number_3">شماره تماس 3</label>
                            <input type="text" class="form-control iran_yekan text-center @error('phone_number_3') is-invalid @enderror masked" data-mask="000000000000" id="phone_number_3" name="phone_number_3" value="{{old("phone_number_3")}}">
                            @error('phone_number_3')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="job_title">عنوان شغل</label>
                            <input type="text" class="form-control iran_yekan text-center @error('job_title') is-invalid @enderror" id="job_title" name="job_title" value="{{old("job_title")}}">
                            @error('job_title')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-3">
                            <label class="col-form-label iran_yekan black_color" for="email">ایمیل</label>
                            <input type="text" class="form-control iran_yekan text-center @error('email') is-invalid @enderror" id="email" name="email" value="{{old("email")}}">
                            @error('email')
                            <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 col-lg-4 col-xl-6">
                            <label class="col-form-label iran_yekan black_color" for="address">آدرس</label>
                            <input type="text" class="form-control iran_yekan text-center" id="address" name="address" value="{{old("address")}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-form-label iran_yekan black_color" for="note">یادداشت</label>
                            <textarea type="text" class="form-control iran_yekan text-center" id="note" name="note">
                    {{old("note")}}
                </textarea>
                        </div>
                        <div class="form-group col-12 text-center pt-3">
                            <button type="submit" form="create_form" class="btn btn-outline-success iran_yekan mr-2 submit_button">
                                <i v-show="button_loading" class="button_loading fa fa-spinner fa-spin mr-2"></i>
                                <i v-show="button_not_loading" class="fa fa-edit button_icon"></i>
                                <span v-show="button_not_loading">ارسال و ذخیره</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <div class="row pt-1 pb-3">
        <div class="col-12">
            <input type="search" class="form-control iran_yekan text-center" placeholder="جستجو در جدول با تمامی عناوین" v-on:input="search_input_filter" aria-describedby="basic-addon3">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover iran_yekan index_table" id="main_table" data-filter='[1,2,3,4,5,6]'>
            <thead class="thead-bg-color">
            <tr>
                <th scope="col">شماره</th>
                <th scope="col">نام</th>
                <th scope="col">عنوان شغل</th>
                <th scope="col" colspan="3">تلفن های تماس</th>
                <th scope="col">ایمیل</th>
                <th scope="col">یادداشت</th>
                <th scope="col">آدرس</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($phonebooks as $phonebook)
                <tr v-on:click="contact_information" data-route="{{route("Phonebook.update",$phonebook->id)}}">
                    <td>{{$phonebook->id}}</td>
                    <td>{{$phonebook->name}}</td>
                    <td>{{$phonebook->job_title}}</td>
                    <td>{{$phonebook->phone_number_1}}</td>
                    <td>{{$phonebook->phone_number_2}}</td>
                    <td>{{$phonebook->phone_number_3}}</td>
                    <td>{{$phonebook->email}}</td>
                    <td>{{$phonebook->note}}</td>
                    <td>{{$phonebook->address}}</td>
                    <td>
                        <form id="delete_form_{{$phonebook->id}}" class="d-inline-block" action="{{route("Phonebook.destroy",$phonebook->id)}}" method="post" data-type="delete" v-on:submit="submit_form">
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

@section('modal_alerts')
    <div class="modal fade iran_yekan" id="contact_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="update_form" method="post" data-type="update" v-on:submit="submit_form">
                    @csrf
                    @method("put")
                    <div class="modal-header">
                        <h6 class="modal-title">ویرایش اطلاعات</h6>
                    </div>
                    <div class="modal-body">
                        <div class="row no-gutters">
                            <input hidden id="contact_id" name="contact_id">
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="name">
                                    نام
                                    <strong class="red_color">*</strong>
                                </label>
                                <input type="text" class="form-control iran_yekan text-center @error('name') is-invalid @enderror" id="e_name" name="name">
                                @error('name')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_phone_number_1">
                                    شماره تماس 1
                                </label>
                                <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000" id="e_phone_number_1" name="phone_number_1">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_phone_number_2">
                                    شماره تماس 2
                                </label>
                                <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000" id="e_phone_number_2" name="phone_number_2">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_phone_number_3">
                                    شماره تماس 3
                                </label>
                                <input type="text" class="form-control iran_yekan text-center masked" data-mask="000000000000" id="e_phone_number_3" name="phone_number_3">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_job_title">
                                    عنوان شغل
                                </label>
                                <input type="text" class="form-control iran_yekan text-center" id="e_job_title" name="job_title">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_email">
                                    ایمیل
                                </label>
                                <input type="text" class="form-control iran_yekan text-center @error('e_email') is-invalid @enderror" id="e_email" name="email">
                                @error('e_email')
                                <span class="invalid-feedback iran_yekan small_font" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_address">
                                    آدرس
                                </label>
                                <input type="text" class="form-control iran_yekan text-center" id="e_address" name="address">
                            </div>
                            <div class="col-12">
                                <label class="col-form-label iran_yekan black_color" for="e_note">
                                    یادداشت
                                </label>
                                <textarea type="text" class="form-control iran_yekan text-center" id="e_note" name="note">
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="update_form" class="btn btn-primary">ویرایش</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
