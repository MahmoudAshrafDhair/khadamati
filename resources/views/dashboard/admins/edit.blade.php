@extends('dashboard.layout.master')

@section('title')
    {{__('admin.admin_edit')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.index')}}" class="text-muted">{{__('aside.admin')}}</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{route('admin.edit',$admin->id)}}" class="text-muted"> {{__('admin.admin_edit')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('admin.admin_edit')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="{{route('admin.update',$admin->id)}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$admin->id}}" name="id">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="offset-5"></div>
                            <div class="col-lg-6">
                                <div class="image-input image-input-outline image-input-circle" id="kt_image_3">
                                    <div class="image-input-wrapper"
                                         style="background-image: url({{$admin->image === null ? asset('assets/image/guest-user.jpg') : asset('storage/'.$admin->image)}})"></div>
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
                                <label>{{__('message.name')}}</label>
                                <input type="text" class="form-control" name="name" value="{{$admin->name}}"
                                       placeholder="{{__('admin.admin_name')}}"/>
                                @error('name')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>{{__('message.email')}}</label>
                                <input type="email" class="form-control" name="email" value="{{$admin->email}}"
                                       placeholder="{{__('admin.admin_email')}}"/>
                                @error('email')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>{{__('message.password')}}</label>
                                <input type="password" class="form-control" name="password"
                                       placeholder="{{__('admin.admin_password')}}"/>
                                @error('password')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label>Roles :</label>
                                <select class="form-control kt-select2" id="kt_select2_3" name="roles_name[]" multiple="multiple">
                                    @foreach ($roles as $role)
                                        <option {{in_array($role,$adminRole) ? 'selected' : false}} value="{{$role}}">{{$role}}</option>
                                    @endforeach
                                </select>
                                @error('roles_name')
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


@stop
