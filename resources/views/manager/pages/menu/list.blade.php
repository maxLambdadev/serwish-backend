@extends('manager.layouts.app')


{{--@section('panel')--}}
{{--    @include('manager.partials.actionPanel',[--}}
{{--        'createRoute' => 'manager.blog.category.create',--}}
{{--        'bulkDelete'=>true,--}}
{{--        'bulkDeleteRoute'=> 'manager.blog.category.bulk.delete'--}}
{{--    ])--}}
{{--@endsection--}}

@push('styles')

@endpush

@push('script')

@endpush

@push('crumbs')
    <li class="breadcrumb-item active">მენიუ</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">მენიუ</h3>
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
            <div class="card-body  p-0 row">

                <div class="col-3">
                    sfsf
                </div>

                <div class="col-9">

                    <div class="dd" id="nestable3">
                        <ol class="dd-list">
                            @foreach($list as $l)
                                @include('manager.pages.menu.each-element',['menu'=>$l])
                            @endforeach
                        </ol>
                    </div>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

@endsection
