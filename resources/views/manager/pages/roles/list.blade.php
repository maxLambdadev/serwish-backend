@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.roles.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.roles.bulk.delete'
    ])
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">უფლებები</h3>
                <div class="card-tools">

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
                                <td>{{$l->name}}</td>
                                <td>
                                    <?php
                                     $buttonData = [
                                         'permission'=>'role',
                                         'editRoute'=>route('manager.roles.edit',['role'=>$l->id]),
                                     ];
                                    if($l->name != 'administrator' && $l->name != 'moderator' && $l->name != 'client' ){
                                        $buttonData['deleteRoute'] = route('manager.roles.destroy',['role'=>$l->id]) ;
                                    }
                                    ?>
                                    @if($l->name != 'administrator'  )
                                        @include('manager.partials.actionButton',$buttonData )
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Empty!</h5>
                        Role List is Empty.
                    </div>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection
