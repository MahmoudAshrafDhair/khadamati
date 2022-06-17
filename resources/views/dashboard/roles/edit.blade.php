@extends('dashboard.layout.master')

@section('title')
    {{__('role.role_edit')}}
@stop

@section('style')

@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}" class="text-muted">{{__('aside.dashboard')}}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('admin.roles.index')}}" class="text-muted">{{__('aside.role')}}</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{route('admin.roles.create')}}" class="text-muted">{{__('role.role_edit')}}</a>
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">{{__('role.role_edit')}}</h3>
                </div>
                <!--begin::Form-->
                <form class="form" method="post" action="{{route('admin.roles.update',$role->id)}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-8">
                                <label>{{__('message.name')}}</label>
                                <input type="text" class="form-control" name="name" value="{{$role->name}}"
                                       placeholder="{{__('role.name_role')}}"/>
                                @error('name')
                                <span class="form-text text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label>{{__('role.permissions')}}</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                    <th colspan="4" class=""><strong>{{__('role.permissions')}}</strong> <input type="checkbox"
                                                                                                 onClick="toggle(this)"/>
                                        Check All<br/></th>
                                    </thead>
                                    <tbody>
                                    @foreach($permissions as $row)
                                        <tr class="">
                                            @foreach($row as $permission)
                                                <td>
                                                    <input type="checkbox" name="permission[]"
                                                           value="{{ $permission->id }}"
                                                           {{in_array($permission->id,$rolePermissions) ? "checked" : false}}
                                                           class="">
                                                    {{ $permission->name }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @error('permission')
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
    <script type="text/javascript">
        function toggle(source) {
            checkboxes = document.getElementsByName('permission[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
@stop
