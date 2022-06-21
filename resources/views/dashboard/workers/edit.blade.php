@extends('dashboard.layout.master')

@section('title')
    {{__('worker.edit')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.workers.edit',$worker->id)}}" class="text-muted">{{__('worker.edit')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('worker.edit')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="{{route('admin.workers.update',$worker->id)}}"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{$worker->id}}">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="offset-5"></div>
                            <div class="col-lg-6">
                                <div class="image-input image-input-outline image-input-circle" id="kt_image_3">
                                    <div class="image-input-wrapper"
                                         style="background-image: url({{$worker->image == null ? asset('assets/image/guest-user.jpg') : asset('storage/'.$worker->image)}})"></div>
                                    <label
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="change" data-toggle="tooltip" title=""
                                        data-original-title="Change avatar">
                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                        <input type="file" name="image"/>
                                        {{--                                        <input type="hidden" name="profile_avatar_remove"/>--}}
                                    </label>
                                    <span
                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                        data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
															<i class="ki ki-bold-close icon-xs text-muted"></i>
														</span>
                                </div>
                                @error('image')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('worker.username')}}</label>
                                <input type="text" class="form-control" name="username"
                                       placeholder="{{__('worker.username')}}" value="{{$worker->username}}"/>
                                @error('username')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('worker.email')}}</label>
                                <input type="email" class="form-control" name="email"
                                       placeholder="{{__('worker.email')}}" value="{{$worker->email}}"/>
                                @error('email')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('worker.password')}}</label>
                                <input type="password" class="form-control" name="password"
                                       placeholder="{{__('worker.password')}}"/>
                                @error('password')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('worker.phone')}}</label>
                                <input type="text" class="form-control" name="phone"
                                       placeholder="{{__('worker.phone')}}" value="{{$worker->phone}}"/>
                                @error('phone')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('worker.city')}}</label>
                                <select class="form-control" id="kt_select2_2" name="city_id"
                                        data-lang="{{ LaravelLocalization::getCurrentLocale() }}">
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" @if ($city->id === $worker->city_id)
                                        selected
                                            @endif>{{$city->name}}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('worker.service')}}</label>
                                <select class="form-control" id="kt_select2_1" name="subCategory_id"
                                        data-lang="{{ LaravelLocalization::getCurrentLocale() }}">
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{$subCategory->id}}" @if ($subCategory->id === $worker->subCategory_id)
                                        selected
                                            @endif>{{$subCategory->name}}</option>
                                    @endforeach
                                </select>
                                @error('subCategory_id')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
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

    <!--begin::Page Scripts(used by this page)-->
    <script src="{{asset('assets/js/pages/crud/file-upload/image-input.js')}}"></script>
    <!--end::Page Scripts-->
    <script>

        {{--$(document).ready(function() {--}}
        {{--    $('.data-2').hide();--}}
        {{--    var select = $('select[name="country_id"]');--}}
        {{--    var countryId = select.val();--}}
        {{--    var url = "{{ URL::to('admin/users/country') }}/" + countryId;--}}
        {{--    var lang = select.data('lang') ;--}}
        {{--    // alert(lang);--}}
        {{--    var name;--}}
        {{--    if (countryId) {--}}
        {{--        $.ajax({--}}
        {{--            url: url,--}}
        {{--            type: "GET",--}}
        {{--            dataType: "json",--}}
        {{--            success: function(data) {--}}
        {{--                $('select[name="city_id"]').empty();--}}
        {{--                $.each(data.cities, function(key, value) {--}}
        {{--                    name = lang === 'en' ? value.name.en : value.name.ar;--}}
        {{--                    $('select[name="city_id"]').append(`<option value="--}}
        {{--                       ${value.id}"> ${name} </option>`);--}}

        {{--                });--}}
        {{--            },--}}
        {{--        });--}}
        {{--    } else {--}}
        {{--        console.log('AJAX load did not work');--}}
        {{--    }--}}



        {{--    $('input[name="user_type"]').change(function(){--}}
        {{--        var type_user = $('input[name="user_type"]:checked').val();--}}
        {{--        // alert(typeof type_user);--}}
        {{--        if(type_user == 1){--}}
        {{--            $('.data-1').show();--}}
        {{--            $('.data-2').hide();--}}
        {{--        }else{--}}
        {{--            $('.data-1').hide();--}}
        {{--            $('.data-2').show();--}}
        {{--        }--}}
        {{--    });--}}


        {{--    $('select[name="country_id"]').on('change', function() {--}}
        {{--        var countryId = $(this).val();--}}
        {{--        var url = "{{ URL::to('admin/users/country') }}/" + countryId;--}}
        {{--        var lang = $(this).data('lang') ;--}}
        {{--        alert(lang);--}}
        {{--         var name;--}}
        {{--        if (countryId) {--}}
        {{--            $.ajax({--}}
        {{--                url: url,--}}
        {{--                type: "GET",--}}
        {{--                dataType: "json",--}}
        {{--                success: function(data) {--}}
        {{--                    $('select[name="city_id"]').empty();--}}
        {{--                    $.each(data.cities, function(key, value) {--}}
        {{--                        name = lang === 'en' ? value.name.en : value.name.ar;--}}
        {{--                        $('select[name="city_id"]').append('<option value="' +--}}
        {{--                            value.id + '">' + name + '</option>');--}}
        {{--                    });--}}
        {{--                },--}}
        {{--            });--}}
        {{--        } else {--}}
        {{--            console.log('AJAX load did not work');--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

    </script>

@stop
