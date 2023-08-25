@extends('manager.layouts.app')



@section('section-title', 'გამოგზავნილი წერილები')

@push('crumbs')
    <li class="breadcrumb-item active">კონტაქტი</li>
@endpush

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">უკუკავშირი</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                @if(!$list->isEmpty())
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>სპეციალისტი</th>
                            <th>თანხა</th>
                            <th>მიმღები</th>
                            <th>ანგარიში</th>
                            <th>სტატუსი</th>
                            <th>მოქმედება</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
                                <td><a href="{{route('manager.users.show',['id'=>$l->user->id])}}">{{$l->user->name}}</a></td>
                                <td>{{number_format($l->amount,2)}}</td>
                                <td>{{$l->fullname == null ? $l->user->name : $l->fullname}}</td>
                                <td>{{$l->iban}}</td>
                                <td >
                                   {!! \App\Helpers\Beautifier::getPaymentStatus($l->status) !!}
                                </td>
                                <td>

                                    <button data-id="{{$l->id}}" data-route="{{route('manager.payment-requests.change-status',[
                                            'status'=>'approved',
                                            'id'=>$l->id
                                        ])}}" class="btn btn-info btn-flat callDoneRequest" @if($l->status != 'in_progress') disabled="disabled" @endif>
                                        დადასტურება
                                    </button>

                                    <button data-id="{{$l->id}}" data-route="{{route('manager.payment-requests.change-status',[
                                            'status'=>'declined',
                                            'id'=>$l->id
                                        ])}}" class="btn btn-danger btn-flat callDoneRequest" @if($l->status != 'in_progress') disabled="disabled" @endif>
                                        უარყოფა
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                        {{__('panel.no_records_found',['record'=>'გადახდის მოთხოვნები'])}}
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
            contact_request_id:id
        },formSuccessHandler)
    })


</script>
@endpush
