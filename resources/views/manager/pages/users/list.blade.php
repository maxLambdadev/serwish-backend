@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
       'createRoute' => 'manager.users.create',
       'bulkDelete'=>true,
       'bulkDeleteRoute'=> 'manager.users.bulk.delete',
       'panelTitle'=>'მენეჯერი',
       'customButtons'=> [
            ['route'=> 'manager.users.export', 'name'=> 'ექსპორტი','icon'=> 'fa-file-export', 'triggerClass'=>'export-btn']
        ]
   ])
@endsection

@push('crumbs')
    <li class="breadcrumb-item active">{{__('users')}}</li>
@endpush
@section('section-title', 'მომხმარებლები')

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">მომხმარებლები</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                @if(!$list->isEmpty())
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="bulkCheck" >
                                            <label for="bulkCheck"></label>
                                        </div>
                                </div>
                            </th>
                            <th>ID</th>
                            <th>სახელი</th>
                            <th>ელ.ფოსტა</th>
                            <th>სერვისები</th>
                            <th>სპეციალისტი</th>
                            <th>მოქმედება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}">
                                <td>
                                    <div class="col-sm-6">
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="item-{{$l->id}}" class="check" value="{{$l->id}}" >
                                                <label for="item-{{$l->id}}">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$l->id}}</td>
                                <td>
                                        <?php
                                        $roleName = $l->roles->first()?$l->roles->first()->name:null;
                                        $color = \App\Helpers\Beautifier::getRoleColor($roleName);
                                        ?>
                                    <span style="color: {{$color}}">
                                        {{$l->name}} ({{$roleName}})
                                    </span>
                                <td>{{$l->email}}</td>
                                <td>{{$l->services->count()}}</td>
                                <td>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input data-user-id="{{$l->id}}" type="checkbox" class="custom-control-input makeSpecialist" id="customSwitch{{$l->id}}" {!!$l->client_type == 'employee'  ? 'checked="checked"' : ''  !!} >
                                            <label class="custom-control-label" for="customSwitch{{$l->id}}">{!!$l->client_type == 'employee' ? 'სპეციალისტი' : 'მომხმარებელი'  !!}</label>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    @if( $l->email != 'metrix.ge@gmail.com')
                                    @include('manager.partials.actionButton',[
                                                                   'permission'=>'user',
                                      'editRoute'=>route('manager.users.edit',['id'=>$l->id]),
                                      'deleteRoute'=>route('manager.users.destroy',['id'=>$l->id]),
                                      'extra'   => [
                                        ['name'=>'დეტალურად','route'=>route('manager.users.show',['id'=>$l->id])]
                                      ]
                                  ])
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {!! $list->links() !!}
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5> <i class="icon fas fa-exclamation-triangle"></i> ცარიელია! </h5>
                    </div>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){

            $('.makeSpecialist').change(function (){
                let isBasicUser = $(this).prop('checked')
                let userType = "კლიენტი";
                if(isBasicUser){
                    userType = "სპეციალისტი"
                }
                let data = {
                    user_id:$(this).attr('data-user-id'),
                    state: isBasicUser
                }

                Swal.fire({
                    title: 'დარწმუნებული ხართ?',
                    text: `ნამდვილად გსურთ გახადოთ ${userType}!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'დიახ!',
                    cancelButtonText: 'გაუქმება!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        post(
                            '{{route('manager.users.specialist.status.change')}}',
                            data,
                            ()=>{},
                            ()=>{}
                        )
                        Swal.fire(
                            'შესრულებულია!',
                            'მოხმარებლის სტატუსი შეიცვალა .',
                            'success'
                        )
                    }
                })

            })
            $('.export-btn').click(function(){
                let exportIds = countChecked()
                $(this).attr('href',`{{route('manager.users.export')}}?exportIds[]=${exportIds.join('&exportIds[]=')}`)
                $(this).click()
            })
        })

        function countChecked(){
            let selector = '.check'
            let ids = [];
            $(selector).each(function(){
                if ($(this).is(':checked'))
                {
                    ids.push($(this).val())
                }
            });
            return ids;
        }
    </script>

@endpush
