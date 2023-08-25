@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.payable-packet.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.payable-packet.bulk.delete'
    ])
@endsection
@section('section-title', 'კატეგორია')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item active">კატეგორიის მართვა</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">პაკეტები</h3>
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
                            <th>სათაური</th>
                            <th>ავტორი</th>
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
                                <td>{{$l->name}}</td>
                                <td>
                                    @php($mainImage = $l->images()->first())
                                    @if($mainImage != null)
                                        <img src="{{asset('/storage/'.$mainImage->path)}}" alt="" width="30" height="40">
                                    @else
{{--                                        <img src="{{asset('/noimage.png)}}" alt="" width="30" height="40">--}}
                                    @endif
                                </td>
                                <td>
                                    @include('manager.partials.actionButton',[
                                        'editRoute'=>route('manager.payable-packet.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.payable-packet.destroy',['id'=>$l->id]),
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
