@extends('dashboard.layout.master')

@section('title')
    {{__('aside.privacy_policy')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.pages.privacy')}}" class="text-muted">{{__('aside.privacy_policy')}}</a>
    </li>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom ">
                <div class="card-header">
                    <h3 class="card-title">{{__('aside.privacy_policy')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="{{route('admin.pages.privacy.update')}}"
                      enctype="multipart/form-data">

                    <div class="card-body">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label>{{__('pages.privacy_policy_ar')}}</label>
                                <textarea class="summernote form-control" id="kt_summernote_1" name="privacy_policy_ar">{{$setting->getTranslation('privacy_policy','ar')}}</textarea>
                                @error('privacy_policy_ar')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label>{{__('pages.privacy_policy_en')}}</label>
                                <textarea class="summernote form-control" id="kt_summernote_1" name="privacy_policy_en">{{$setting->getTranslation('privacy_policy','en')}}</textarea>
                                @error('privacy_policy_en')
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
                            <div class="col-lg-6 text-lg-right">
{{--                                <button type="reset" class="btn btn-danger">Delete</button>--}}
                            </div>
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
    <script src="{{asset('assets/js/pages/crud/forms/editors/summernote.js')}}"></script>
    <!--end::Page Scripts-->
@stop
