@extends('manager.layouts.app')



@section('section-title', 'გამოგზავნილი წერილები')

@push('crumbs')
    <li class="breadcrumb-item active">შეკვეთები</li>
@endpush

@section('content')

    @include('manager.partials.actionPanel',[
           'panelTitle'=>'მენეჯერი',
           'customButtons'=> [
                ['route'=> 'manager.orders.export', 'name'=> 'ექსპორტი','icon'=> 'fa-file-export', 'triggerClass'=>'export-btn']
            ]
       ])

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools " style="width: 25%">
                    <form action="" method="get" class="row">

                        <div class="form-group col-6">
                            <div class="form-group">
                                <div class="select2-purple">
                                    <select name="room_state" class="select2 form-control review-change"
                                            data-placeholder="აირჩიეთ მშობელი კატეგორია" data-dropdown-css-class="select2-purple"
                                            style="width: 100%;"  >
                                        <option value="all">ყველა</option>
                                        <option value="started" {!! request()->get('room_state') && request()->get('room_state')  == 'started' ? 'selected' : '' !!}>ჯგუფი შეიქმნა</option>
                                        <option value="in_progress" {!!request()->get('room_state') && request()->get('room_state') == 'in_progress' ? 'selected' : '' !!}>შეკვეთა დაიწყო</option>
                                        <option value="order_closed" {!! request()->get('room_state') && request()->get('room_state') == 'room_state' ? 'selected' : '' !!}>შეკვეთა დასრულდა</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="padding-top:1%; visibility: hidden">
                            <div class="input-group input-group-md div " style="width: 150px;">
                                <input type="text" name="title" class="form-control float-right" placeholder="ძებნა" @if(request()->has('title')) value="{{request()->get('title')}}" @endif>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default filter-form">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
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
                            <th>სერვისი</th>
                            <th>სტატუსი</th>
                            <th>შემკვეთი</th>
                            <th>შემსრულებელი</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
                                <td>{{ $l->service->title}}</td>
                                <td>{!! \App\Helpers\Beautifier::getOrderStatus($l->room_state) !!}</td>
                                <td>
                                    <a href="{{route('manager.users.show',['id'=>$l->customer->id])}}">{{$l->customer->name}}</a>
                                </td>
                                <td>
                                    <a href="{{route('manager.users.show',['id'=>$l->specialist->id])}}">{{$l->specialist->name}}</a>
                                </td>
                                <td>
                                <td>
                                    @include('manager.partials.actionButton',[
                                       'editRoute'=>route('manager.orders.show',['id'=>$l->id]),
                                       'customEditName'=> 'მიმოწერა',
                                       'deleteRoute'=>route('manager.orders.destroy',['id'=>$l->id]),
                                   ])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        {{__('panel.no_records_found',['record'=>'შეკვეთები'])}}
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
    $(document).ready(function (){
        $('.review-change').change(function (){
            $('.filter-form').trigger('click')
        })
    })

    $('.export-btn').click(function(){
        $(this).attr('href',`{{route('manager.orders.export')}}?exportIds[]=`)
        $(this).click()
    })

</script>
@endpush
