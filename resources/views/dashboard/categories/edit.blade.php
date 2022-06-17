@extends('dashboard.layout.master')

@section('title')
    {{__('aside.category')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.categories.index')}}" class="text-muted"> {{__('aside.category')}}</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{route('admin.categories.edit',$category->id)}}" class="text-muted">{{__('category.edit')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('category.edit')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="{{route('admin.categories.update',$category->id)}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$category->id}}">
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('message.name_ar')}}</label>
                                <input type="text" class="form-control" name="name_ar" value="{{$category->getTranslation('name','ar')}}"
                                       placeholder="{{__('category.name_ar')}}"/>
                                @error('name_ar')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('message.name_en')}}</label>
                                <input type="text" class="form-control" name="name_en" value="{{$category->getTranslation('name','en')}}"
                                       placeholder="{{__('category.name_en')}}"/>
                                @error('name_en')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('message.image')}}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile" name="image"
                                           onchange="readURL(this);"/>
                                    <label class="custom-file-label"
                                           for="customFile">{{__('category.image_category')}}</label>
                                </div>
                                @error('image')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="offset-2"></div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <img id="blah" class="media-object img-thumbnail" width="150" height="150"
                                         {{--                                         src="{{$setting->who_are_we_image == null ? asset('assets/image/defoult.png') : asset('storage/'.$setting->who_are_we_image)}}"--}}
                                         src="{{$category->image == null ?asset('assets/image/defoult.png'): asset('storage/'.$category->image)}}"
                                         alt="Avatar">
                                </div>
                            </div>
                        </div>

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
