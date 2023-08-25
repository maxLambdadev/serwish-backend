@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.ads.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.ads.bulk.delete'
    ])
@endsection
@section('section-title', 'კატეგორია')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item active">ბანერების მენეჯერი</li>
@endpush

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ბანერების</h3>
                <div class="card-tools">
                    <form action="" method="get">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="name" class="form-control float-right" placeholder="ძებნა">
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
                            <th>დასახელება</th>
                            <th>გვერდი</th>
                            <th>ბმული</th>
                            <th>პოზიცია</th>
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
                                <td>{{$l->name}}</td>
                                <td>{{$l->page}}</td>
                                <td>{{\Illuminate\Support\Str::limit($l->link,'70','...')}}</td>
                                <td>{{$l->order}}</td>
                                <td>
                                    @include('manager.partials.actionButton',[
                                        'editRoute'=>route('manager.ads.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.ads.destroy',['id'=>$l->id]),
                                    ])
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
