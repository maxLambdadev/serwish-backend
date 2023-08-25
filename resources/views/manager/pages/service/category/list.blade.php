@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'createRoute' => 'manager.services.category.create',
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.services.category.bulk.delete',
          'customButtons'=> [
            ['route'=> 'manager.services.category.export', 'name'=> 'ექსპორტი','icon'=> 'fa-file-export', 'triggerClass'=>'export-btn']
        ]
    ])
@endsection

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item active">კატეგორიის მართვა</li>
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
                            <th>სათაური</th>
                            <th>მშობელი</th>
                            <th>სურათი</th>
                            <th>სერვისი</th>
                            <th>სტატუსი</th>
                            <th>ქმედება</th>
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
                                <td>{{\Illuminate\Support\Str::limit($l->title,10)}}</td>
                                <td>{{$l->parent != null ? $l->parent->title : 'არ ყავს მშობელი'}}</td>
                                <td>
                                    @php($mainImage = $l->images()->first())
                                    @if($mainImage != null)
                                        <img src="{{asset('/storage/'.$mainImage->path)}}" alt="" width="30" height="40">
                                    @else
{{--                                        <img src="{{asset('/noimage.png)}}" alt="" width="30" height="40">--}}
                                    @endif
                                </td>
                                <td>
                                    {{$l->services->count()}} - სერვისი
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{$l->id}}" {!!$l->isActive ? 'checked="checked"' : ''  !!} disabled>
                                            <label class="custom-control-label" for="customSwitch{{$l->id}}">{!!$l->isActive ? 'აქტიური' : 'გამორთული'  !!}</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @include('manager.partials.actionButton',[
                                                    'permission'=>'special-category',
                                        'editRoute'=>route('manager.services.category.edit',['id'=>$l->id]),
                                        'deleteRoute'=>route('manager.services.category.destroy',['id'=>$l->id]),
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
        $(document).ready(function(){

            $('.export-btn').click(function(){
                let exportIds = countChecked()
                $(this).attr('href', `{{route('manager.services.category.export')}}?exportIds[]=${exportIds.join('&exportIds[]=')}`)
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
