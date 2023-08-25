@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.faq.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.faq.bulk.delete',

    ])
@endsection

@push('crumbs')
    <li class="breadcrumb-item">მართვა</li>
    <li class="breadcrumb-item active">როგორ მუშაობს</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">კატეგორიები</h3>
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
                            <th>აღწერა</th>
                            <th>სურათი</th>
                            <th>ბმული</th>
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
                                <td>{{$l->title}}</td>
                                <td>{!! \Illuminate\Support\Str::limit(strip_tags($l->description),30, '...') !!}</td>
                                <td>
                                    @php($mainImage = $l->images()->first())
                                    @if($mainImage != null)
                                        <img src="{{asset('/storage/'.$mainImage->path)}}" alt="" width="30" height="40">
                                    @else
{{--                                        <img src="{{asset('/noimage.png)}}" alt="" width="30" height="40">--}}
                                    @endif
                                </td>

                                <td>{{$l->button_link}}</td>

                                <td>
                                    @include('manager.partials.actionButton',[
                                        'editRoute'=>route('manager.faq.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.faq.destroy',['id'=>$l->id]),
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

@push('script')

    <script>

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
