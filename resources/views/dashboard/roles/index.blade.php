@extends('dashboard.layout.master')

@section('title')
    {{__('aside.role')}}
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
@stop

@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('role.role_title')}}
{{--                    <span class="text-muted pt-2 font-size-sm d-block">Javascript array as data source</span></h3>--}}
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{route('admin.roles.create')}}" class="btn btn-info font-weight-bolder">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-plus"></i>
											</span>{{__('role.role_Add')}}</a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('message.name')}}</th>
                    <th>{{__('message.action')}}</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0 ?>
                @foreach ($roles as $role)
                    <tr>
                        <?php $i++ ?>
                        <td>{{$i}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                            <a href="{{route('admin.roles.edit',$role->id)}}"
                               class="btn btn-icon btn-xs btn-info">
                                <i class="flaticon2-edit"></i>
                            </a>
                            <button class="btn btn-icon btn-xs btn-danger btn-delete" data-id="{{$role->id}}"
                                    data-name="{{$role->name}}" data-toggle="modal" data-target="#delete-modal">
                                <i class="flaticon2-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable -->

        </div>
    </div>
    <!--end::Card-->

    <div class="modal fade text-left delete-modal" id="delete-modal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel10"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                    <h4 class="modal-title white"
                        id="myModalLabel10">{{__('role.delete_role')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.roles.destroy')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <h4>{{__('message.delete_message')}} </h4><br>
                        <input type="hidden" name="role_id" id="role_id" value="">
                        <input type="text" class="form-control" name="role_name" id="role_name"
                               readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">
                            {{__('message.cancel')}}
                        </button>
                        <button type="submit"
                                class="btn btn-outline-danger"> {{__('message.delete')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('script')

    <script>

            $(document).on('show.bs.modal','#delete-modal',function (event) {
                let button = $(event.relatedTarget);
                let user_id = button.data('id');
                let user_name = button.data('name');
                var model = $(this);
                model.find('.modal-body #role_id').val(user_id);
                model.find('.modal-body #role_name').val(user_name);
            })

    </script>
@stop
