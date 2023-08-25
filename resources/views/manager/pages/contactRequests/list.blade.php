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
                            <th>ტელეფონი</th>
                            <th>სათაური</th>
                            <th>აღწერა</th>
                            <th>თემა</th>
                            <th>ელ.ფოსტა</th>
                            <th>წაკითხული?</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($list as $l)
                            <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
                                <td>{{$l->phone}}</td>
                                <td>{{$l->title}}</td>
                                <td data-toggle="tooltip" data-placement="bottom" title="{{$l->description}}">
                                    {{ \Illuminate\Support\Str::limit($l->description,20) }}
                                </td>
                                <td>{{$l->subject == null ? 'ცარიელი' : $l->subject}}</td>
                                <td>{{$l->email == null ? 'ცარიელი' : $l->email}}</td>

                                <td>
                                    <button data-id="{{$l->id}}" data-route="{{route('manager.contact-requests.seen')}}" class="btn btn-info btn-flat callDoneRequest" @if($l->seen) disabled="disabled" @endif>
                                        გახადე წაკითხული
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
            contact_request_id:id
        },formSuccessHandler)
    })


</script>
@endpush
