@extends('dashboard.layout.master')

@section('title')
    {{__('aside.order')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.orders.index')}}" class="text-muted"> {{__('aside.order')}}</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{route('admin.orders.show',$order->id)}}" class="text-muted">{{__('order.show')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('order.show')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="#" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('order.user')}}</label>
                                <input type="text" class="form-control" name="username"
                                       value="{{$order->user->username}}"
                                       placeholder="{{__('order.user')}}" disabled/>
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('order.worker')}}</label>
                                <input type="text" class="form-control" name="worker"
                                       value="{{$order->worker->username}}"
                                       placeholder="{{__('order.order')}}" disabled/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('order.subCategory')}}</label>
                                <input type="text" class="form-control" name="username"
                                       value="{{$order->subCategories->getTranslation('name',\Illuminate\Support\Facades\App::getLocale())}}"
                                       placeholder="{{__('order.subCategory')}}" disabled/>
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('order.time_type')}}</label>
                                <input type="text" class="form-control" name="worker"
                                       value="@if($order->time_type == 1){{__('api.now')}} @else{{__('api.later')}} @endif"
                                       placeholder="{{__('order.time_type')}}" disabled/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('order.longitude')}}</label>
                                <input type="text" class="form-control" name="longitude" value="{{$order->longitude}}"
                                       placeholder="{{__('order.longitude')}}" disabled/>
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('order.latitude')}}</label>
                                <input type="text" class="form-control" name="latitude" value="{{$order->latitude}}"
                                       placeholder="{{__('order.latitude')}}" disabled/>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('message.image')}}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="image"
                                           onchange="readURL(this);" disabled/>
                                    <label class="custom-file-label"
                                           for="customFile">{{__('order.image_chose')}}</label>
                                </div>
                            </div>

                            <div class="offset-2"></div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <img id="blah" class="media-object img-thumbnail" width="150" height="150"
                                         {{--                                         src="{{$setting->who_are_we_image == null ? asset('assets/image/defoult.png') : asset('storage/'.$setting->who_are_we_image)}}"--}}
                                         src="{{$order->image == null ?asset('assets/image/defoult.png'): asset('storage/'.$order->image)}}"
                                         alt="Avatar">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('order.description')}}</label>
                                <textarea type="text" class="form-control" name="description" cols="4" rows="4"
                                          placeholder="{{__('order.description')}}" disabled>{{$order->description}}</textarea>
                            </div>


                            <div class="col-lg-6">
                                <label>{{__('order.states')}}</label>
                                <input type="text" class="form-control" name="date"
                                       value="@if($order->type == 1) {{__('order.pending')}}
                                       @elseif ($order->type == 2) {{__('order.current')}}  @elseif ($order->type == 3) {{__('order.rejected')}}
                                       @else {{__('order.completed')}} @endif"
                                       placeholder="{{__('order.states')}}" disabled/>
                            </div>
                        </div>

                        @if ($order->time_type == 2)
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>{{__('order.date')}}</label>
                                    <input type="text" class="form-control" name="date" value="{{$order->date}}"
                                           placeholder="{{__('order.date')}}" disabled/>
                                </div>
                            </div>
                        @endif


                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary mr-2">{{__('message.save')}}</button>
                                {{--                                <button type="reset" class="btn btn-secondary">Cancel</button>--}}
                            </div>
                            {{--                            <div class="col-lg-6 text-lg-right">--}}
                            {{--                                <button type="reset" class="btn btn-danger">Delete</button>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
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
