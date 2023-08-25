@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.blog.post.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.blog.post.bulk.delete',
        'disableEdit'=>true,
        'customButtons'=>[
            'route'=> [
                  'name'=>'კატეგორიები',
                'route'=>route('manager.blog.category.index'),
                'href'=>route('manager.blog.category.index'),
                'icon'=>'fa-circle'
            ]

]
    ])

@endsection
@section('section-title', 'სიახლეები')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item active">სიახლეების მართვა</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">სიახლეები</h3>
                <div class="card-tools">
                    <form action="" method="get">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="title" class="form-control float-right" placeholder="ძებნა">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
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
                            <th>სათაური</th>
                            <th>ავტორი</th>
                            <th>დარჩენილი დრო</th>
                            <th>სტატუსი</th>
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
                                <td>{{$l->title}}</td>
                                <td>
                                    <?php
                                        $roleName = $l->author->roles->first()->name;
                                        $color = \App\Helpers\Beautifier::getRoleColor($roleName);
                                        ?>
                                    <span style="color: {{$color}}">
                                        {{$l->author->name}} ({{$roleName}})
                                    </span>
                                </td>
                                <td>
                                    @php($createdDate = \Carbon\Carbon::parse($l->created_at))
                                    @php($diff = $createdDate->diffInDays(\Carbon\Carbon::now()))


                                    @if($diff <= 10)

                                        <small style="color: black">დარჩენილია {{ $diff }} დღე</small>

                                        @else
                                        <small style="color: red">დრო ამოიწუერა</small>
                                    @endif

                                </td>
                                <td>
                                    @if($diff <= 10)
                                    @include('manager.partials.actionButton',[
                                                            'permission'=>'blog',

                                        'editRoute'=>route('manager.blog.post.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.blog.post.destroy',['id'=>$l->id]),
                                    ])
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        {{__('panel.no_records_found')}}
                    </div>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            {!! $list->links() !!}

        </div>
        <!-- /.card -->
    </div>
@endsection
