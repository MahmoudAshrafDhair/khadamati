@extends('dashboard.layout.master')

@section('title')
    {{__('contacts.title_worker')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.contact.index.worker')}}" class="text-muted"> {{__('contacts.title_worker')}}</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{route('admin.contact.show.worker',$contact->id)}}" class="text-muted">{{__('contacts.show_worker')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('contacts.show_worker')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="#" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('contacts.username')}}</label>
                                <input type="text" class="form-control" name="username"
                                       value="{{$contact->name}}"
                                       placeholder="{{__('contacts.username')}}" disabled/>
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('contacts.email')}}</label>
                                <input type="text" class="form-control" name="email"
                                       value="{{$contact->email}}"
                                       placeholder="{{__('contacts.email')}}" disabled/>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('contacts.message')}}</label>
                                <textarea type="text" class="form-control" name="message" cols="4" rows="4"
                                          placeholder="{{__('contacts.message')}}" disabled>{{$contact->message}}</textarea>
                            </div>


                        </div>


                    </div>
{{--                    <div class="card-footer">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-lg-6">--}}
{{--                                <button type="submit" class="btn btn-primary mr-2">{{__('message.save')}}</button>--}}
{{--                                --}}{{--                                <button type="reset" class="btn btn-secondary">Cancel</button>--}}
{{--                            </div>--}}
{{--                            --}}{{--                            <div class="col-lg-6 text-lg-right">--}}
{{--                            --}}{{--                                <button type="reset" class="btn btn-danger">Delete</button>--}}
{{--                            --}}{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').show();
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
