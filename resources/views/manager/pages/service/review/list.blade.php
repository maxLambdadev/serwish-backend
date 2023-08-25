@extends('manager.layouts.app')


@section('panel')
    @include('manager.partials.actionPanel',[
        'bulkDelete'=>true,
        'bulkDeleteRoute'=> 'manager.services.review.bulk.delete',
        'customEditName'=> 'დეტალურად',
        'customEditIcon'=> 'fa-eye',
    ])
@endsection

@push('crumbs')
    <li class="breadcrumb-item">სერვისები</li>
    <li class="breadcrumb-item active">მომსახურებები</li>
@endpush
@section('section-title', 'სერვისები')

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">სერვისები</h3>

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
                            <th>სერვისი</th>
                            <th>სპეციალისტი</th>
                            <th>მომხმარებელი</th>
                            <th>მოსწონს</th>
                            <th>მოქმედება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
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
                                <td>
                                    <a href="{{route('manager.services.service.show',['id'=>$l->service->id])}}" target="_blank" title="{{$l->service->title}}">
                                        {{\Illuminate\Support\Str::limit($l->service->title,20)}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('manager.users.show',['id'=>$l->service->specialist->id])}}" target="_blank" title="{{$l->service->specialist->name}}">
                                        {{\Illuminate\Support\Str::limit($l->service->specialist->name,20)}}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{route('manager.users.edit',['id'=>$l->user->id])}}" target="_blank" title="{{$l->user->name}}">
                                        {{\Illuminate\Support\Str::limit($l->user->name,20)}}
                                    </a>
                                </td>
                                <td>
                                    {!!  $l->likes ? '<i style="color:green" class="fa fa-thumbs-up" aria-hidden="true"></i>' : '<i style="color:red" class="fa fa-thumbs-down" aria-hidden="true"></i>' !!}
                                </td>

                                <td>
                                    @include('manager.partials.actionButton',[
                                                                    'permission'=>'service',
                                        'editRoute'=>route('manager.services.review.show',['id'=>$l->id]),
                                        'customEditName'=> 'დეტალურად',
                                        'deleteRoute'=>route('manager.services.review.destroy',['id'=>$l->id]),
                                    ])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        შეფასებები არ არის
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
                $(this).attr('href',`{{route('manager.services.service.export')}}?exportIds[]=${exportIds.join('&exportIds[]=')}`)
                $(this).click()
            })

            $('.review-change').change(function (){
                $('.filter-form').trigger('click')
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
