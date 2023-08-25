@extends('manager.layouts.app')



@section('section-title', 'ზარის მოთხოვნები')

@push('crumbs')
    <li class="breadcrumb-item active">ზარის მოთხოვნები</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ზარის მოთხოვნები</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                @if(!$list->isEmpty())
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>ტელეფონის ნომერი</th>
                            <th>კატეგორია</th>
                            <th>უკუ კავშირი?</th>
                            <th>შესრულება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}" @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >

                                <td>{{$l->id}}</td>
                                <td>{{$l->phone_number}}</td>
                                <td>{{$l->category == null ? ''  : $l->category->title}}</td>
                                <td>
                                    
                                    @if($l->is_called)
                                        დარეკილი
                                        @else
                                        <p style="color: red">დასარეკი</p>
                                    @endif
                                </td>
                                <td>

                                    <button data-id="{{$l->id}}" data-route="{{route('manager.call-requests.is-called')}}" class="btn btn-info btn-flat callDoneRequest" @if($l->is_called) disabled="disabled" @endif>
                                        შესრულდა
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        {{__('panel.no_records_found',['record'=>'ზარის მოთხოვნები'])}}
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
    $('.callDoneRequest').on('click',function (e){
        e.preventDefault();
        var route = $(this).attr('data-route')
        var id = $(this).attr('data-id')
        postRequest(route, {
            call_request_id:id
        },formSuccessHandler)
    })


</script>
@endpush
