@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.configuration.locales.create'
    ])
@endsection

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
                            <th>ID</th>
                            <th>სახელი</th>
                            <th>კოდი</th>
                            <th>აქტიური</th>
                            <th>მთავარი ენა</th>
                            <th>მოქმედება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}">
                                <td>{{$l->id}}</td>
                                <td>{{$l->name}}</td>
                                <td>{{$l->iso_code}}</td>
                                <td>
                                    <input type="checkbox" name="my-checkbox" class="switcher" data-type="is_active"
                                           @if($l->is_active)
                                           checked
                                           @endif
                                           disabled
                                           data-bootstrap-switch>
                                </td>
                                <td>
                                    <input type="checkbox" name="my-checkbox" class="switcher" data-type="is_default"
                                           @if($l->is_default)
                                           checked
                                           @endif
                                               disabled
                                           data-bootstrap-switch>
                                </td>

                                <td>
                                    @include('manager.partials.actionButton',[
                                        'editRoute'=>route('manager.configuration.locales.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.configuration.locales.destroy',['id'=>$l->id]),
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

