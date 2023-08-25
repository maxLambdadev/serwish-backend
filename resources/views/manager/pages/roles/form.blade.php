@extends('manager.layouts.app')

@section('section-title', 'უფლების მართვა')

@section('content')
    <div class="col-md-6">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">შესაძლებლობები </h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <table class="table table-hover table-responsive-lg text-nowrap">
                    <thead>
                    <tr>
                        <th>სახელი</th>
                        <th style="width: 20px;">შექმნა</th>
                        <th style="width: 20px;">წაშლა</th>
                        <th style="width: 20px;">რედაქტირება</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($permissions as $l)
                        @php($rolePerm = \App\Models\RoleHasPermissions::where('role_id','=',isset($entity) ? $entity->id : null)->where('permission_id','=',$l->id)->first())
                        @isset($entity)
                        <tr id="tr-{{$l->id}}">
                            <td>{{$l->description}}</td>
                            <td>
                                <div class="col-sm-2">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">

                                            <input type="checkbox" id="item-{{$l->id}}-can_add" data-perm-id="{{$l->id}}" data-id="{{$entity->id}}" data-permission="can_add" class="can_add check" value="{{$l->id}}"
                                                   @if($rolePerm != null && $rolePerm->can_add)
                                                       checked
                                                   @endif
                                            >
                                            <label for="item-{{$l->id}}-can_add">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-sm-2">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="item-{{$l->id}}-can_delete" data-perm-id="{{$l->id}}" data-id="{{$entity->id}}" data-permission="can_delete" class="can_delete check" value="{{$l->id}}"
                                                   @if($rolePerm != null && $rolePerm->can_delete)
                                                    checked
                                                  @endif
                                            >
                                            <label for="item-{{$l->id}}-can_delete">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col-sm-2">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="item-{{$l->id}}-can_edit" data-perm-id="{{$l->id}}" data-id="{{$entity->id}}" data-permission="can_edit" class="can_edit check" value="{{$l->id}}"

                                                @if($rolePerm != null && $rolePerm->can_edit)
                                                   checked
                                                @endif
                                            >
                                            <label for="item-{{$l->id}}-can_edit">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- jquery validation -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">უფლების ფორმა </h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="quickForm" action="{{route('manager.roles.store')}}">
                @csrf

                @isset($entity)
                    <input type="hidden" name="id" value="{{$entity->id}}">
                @endif

                <div class="card-body">

                    <div class="form-group">
                        <label for="name">უფლება</label>
                        <input type="text" name="name" class="form-control" id="name"
                               @isset($entity)
                                   value="{{$entity->name}}"
                               @endif
                               placeholder="შეიყვანეთ უფლების დასახელება">
                    </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">შენახვა</button>
                    <button type="reset" class="btn btn-danger">გასუფთავება</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>




@endsection

@push('script')

    <script>
        $(function () {
            validate(
                {
                    role: {
                        required: true,
                    }
                },
                {
                    role: {
                        required: "Please enter a role name",
                    }
                },
            )

            $('.submitAndStay').click(function (e){
                e.preventDefault()
                $('#quickForm').submit()
            })

            $('#quickForm').submit(function (e){
                e.preventDefault()

                post(
                    $(this).attr('action'),
                    $(this).serialize(),
                    formSuccessHandler,
                    formErrorHandler
                )
            })


            $('.check').on('click',function () {
                let value =  $(this).prop('checked')
                let updatePerm =  $(this).attr('data-permission')
                let roleId =  $(this).attr('data-id')
                let permId =  $(this).attr('data-perm-id')
                post(
                    '{{route('manager.roles.update.permission')}}',
                    {
                        action:updatePerm,
                        value:value,
                        id:roleId,
                        permId:permId
                    },
                    {},
                    {}
                )

            })

            $('#bulkCheckAdd').on('click',function (){
                let data = []
                let roleId = $('[data-permission="can_add"]').attr('data-id')
                let permId = $('[data-permission="can_add"]').attr('data-perm-id')
                $('[data-permission="can_add"]').prop('checked', $(this).prop('checked'));
                $('[data-permission="can_add"]').each(function (i){
                    console.log(i)
                    data.push({
                        action:'can_add',
                        value:$(this).prop('checked'),
                        id:roleId,
                        permId:permId
                    })
                })
                post(
                    '{{route('manager.roles.update.permission')}}',
                    {
                        data
                    }, {}, {}
                )

                $(this).prop('checked', $(this).prop('checked'));
            })

            $('#bulkCheckDelete').on('click',function (){
                $('[data-permission="can_delete"]').prop('checked', $(this).prop('checked'));
                $(this).prop('checked', $(this).prop('checked'));
            })
        });
    </script>

@endpush
