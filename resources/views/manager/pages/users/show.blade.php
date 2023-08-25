@extends('manager.layouts.app')
@section('section-title', 'პროფილი')

@section('content')

    <div class="col-md-3">




        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @php
                        $img = $user->image();
                    @endphp
                    <img src="{{asset($img ? 'storage/'.$img->path : 'manager/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="{{$user->email}}"
                    width="240" height="240"
                    >
                </div>

                <h3 class="profile-username text-center">სახელი გვარი</h3>

                <p class="text-muted text-center">სპეციალობა</p>

                <ul class="list-group list-group-unbordered mb-3">
                    @if($user->client_type == 'employee' )
                        <li class="list-group-item">
                            <b>ბალანსი</b> <a class="float-right">
                                @if($user->balance == null)
                                    0
                               @else
                                {{number_format($user->balance->balance, 2)}}
                                @endif
                            </a>
                        </li>
                    @endif
                    <li class="list-group-item">
                        <b>შეკვეთები</b> <a class="float-right">{{$orders->count()}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>სერვისები</b> <a class="float-right">{{$user->services->count()}}</a>
                    </li>

                </ul>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">ჩემს შესახებ</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> ტელ</strong>

                <p class="text-muted">
                    {{$user->phone_number}}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> ტიპი</strong>

                <p class="text-muted">  {!! $user->client_type == 'employee' ? 'სპეციალისტი' : 'მომხმარებელი'  !!}      </p>



            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-md-9">
        <div class="card bg-gradient-info">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-th mr-1"></i>
                    სტატისტიკა
                </h3>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row">
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{$reviewData[1]}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                        <div class="text-white">არ დამირეკავს</div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{$reviewData[2]}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                        <div class="text-white">ვერ დავუკავშირდი</div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{$reviewData[3]}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                        <div class="text-white">არ მიპასუხა</div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{$reviewData[4]}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                        <div class="text-white"> ვერ შევთანხმდით</div>
                    </div>
                    <div class="col-2 text-center">
                        <input type="text" class="knob" data-readonly="true" value="{{$reviewData[5]}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                        <div class="text-white"> შევთანხმდით</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">მომსახურებები</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">შეკვეთები</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="activity">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">სერვისები</h3>
                                <div class="card-tools">
                                    <form action="" method="get">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="title" class="form-control float-right" placeholder="ძებნა" @if(request()->has('title')) value="{{request()->get('title')}}" @endif>
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
                                @if(!$services->isEmpty())
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
                                            <th>სერვისი</th>
                                            <th>შემსრულებელი</th>
                                            <th>სპეციალობა</th>
                                            <th>სტატუსი</th>
                                            <th>მოქმედება</th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        @foreach($services as $l)
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
                                                <td>
                                                    {{$l->specialist->name}}
                                                </td>
                                                <td>
                                                    <ul>
                                                        @foreach($l->categories as $category)
                                                            <li> {{$category->title == null ? 'სახელი არ აქვს' : $category->title}}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{$l->id}}" {!!$l->is_active ? 'checked="checked"' : ''  !!} disabled>
                                                            <label class="custom-control-label" for="customSwitch{{$l->id}}">{!!$l->is_active ? 'აქტიური' : 'გამორთული'  !!}</label>
                                                        </div>
                                                    </div>


                                                </td>
                                                <td>
                                                    @include('manager.partials.actionButton',[
                                                        'editRoute'=>route('manager.services.service.show',['id'=>$l->id]),
                                                        'customEditName'=> 'დეტალურად',
                                                        'deleteRoute'=>route('manager.services.service.destroy',['id'=>$l->id]),
                                                    ])
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                @else
                                    <div class="alert alert-warning alert-dismissible">
                                        <h5><i class="icon fas fa-exclamation-triangle"></i> {{__('panel.empty')}} </h5>
                                        სერვისები არ არის
                                    </div>
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            {!! $services->links() !!}
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->


                        <table class="table table-responsive table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>სერვისი</th>
                                <th>სტატუსი</th>
                                <th>შემკვეთი</th>
                                <th>შემსრულებელი</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $l)
                                <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
                                    <td>{{ \Illuminate\Support\Str::limit($l->service->title,20, '...')}}</td>
                                    <td>{!! \App\Helpers\Beautifier::getOrderStatus($l->room_state) !!}</td>
                                    <td>
                                        <a href="{{route('manager.users.show',['id'=>$l->customer->id])}}">{{\Illuminate\Support\Str::limit($l->customer->name,20,'...')}}</a>
                                    </td>
                                    <td>
                                        <a href="{{route('manager.users.show',['id'=>$l->specialist->id])}}">{{$l->specialist->name}}</a>
                                    </td>
                                    <td>
                                    <td>
                                        @include('manager.partials.actionButton',[
                                           'editRoute'=>route('manager.orders.show',['id'=>$l->id]),
                                           'customEditName'=> 'მიმოწერა',
                                       ])
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                    </div>
                    <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

@endsection

@push('script')
    <script>

    </script>
@endpush
