@extends('manager.layouts.app')


@section('section-title', 'მიმოწერა')

@push('crumbs')
    <li class="breadcrumb-item">შეკვეთები</li>
    <li class="breadcrumb-item active">მიმოწერა</li>
@endpush

@section('content')


    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">მიმოწერა</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">

                @if(!$messages->isEmpty())
                    <main class="content">
                        <div class="container p-0">
                            <div class="card">
                                <div class="row g-0">
                                    <div class="col-12 col-lg-12 col-xl-12">
                                        <div class="py-2 px-4 border-bottom d-none d-lg-block">
                                            <div class="d-flex align-items-center py-1">
                                                <div class="position-relative">
                                                    <?php
                                                     $serviceImage = $entity->service->images->first();
                                                     $serviceImage = $serviceImage == null ? "https://bootdey.com/img/Content/avatar/avatar1.png" : asset('/storage/'.$serviceImage->path) ;
                                                    ?>
                                                    <img src="{{$serviceImage}}" class="rounded-circle mr-1" alt="{{$entity->service->title}}" width="40" height="40">
                                                </div>
                                                <div class="flex-grow-1 pl-3">
                                                    <strong>{{$entity->service->title}}</strong>
                                                    <div> სტატუსი: {!! \App\Helpers\Beautifier::getOrderStatus($entity->room_state) !!}</div>
                                                    <div class="text-muted small"><em>შექმნის თარიღი {{$entity->created_at}}</em></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-relative" >
                                            <div class="chat-messages p-4" >
                                                @foreach($messages->sort() as $msg)
                                                    <?php
                                                    $specialistSender = $msg->group->customer->id == $msg->sender_id;
                                                    if ($specialistSender){
                                                        $cImg = $msg->group->customer->images()->first();
                                                        $imgPath = $cImg == null ? "https://bootdey.com/img/Content/avatar/avatar1.png" : asset('/storage/'.$cImg->path) ;
                                                    }else{
                                                        $cImg = $msg->group->specialist->images()->first();
                                                        $imgPath = $cImg == null ? "https://bootdey.com/img/Content/avatar/avatar3.png" : asset('/storage/'.$cImg->path) ;
                                                    }
                                                    ?>

                                                        @if($msg->type == 'change_state')
                                                            <div class="{!! $specialistSender ? 'chat-message-right' : 'chat-message-left' !!} pb-4">
                                                                <div>
                                                                    <img src="{{$imgPath}}" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
                                                                    <div class="text-muted small text-nowrap mt-2">{{$msg->created_at}}</div>
                                                                </div>
                                                                <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                                                    <div class="font-weight-bold mb-1">{{$specialistSender ? $msg->group->customer->name : $msg->group->specialist->name  }}</div>
                                                                    {!! \App\Helpers\Beautifier::getOrderStatus(explode('/change_state ',$msg->message)[1]) !!}
                                                                </div>
                                                            </div>
                                                        @else

                                                            <div class="{!! $specialistSender ? 'chat-message-right' : 'chat-message-left' !!} pb-4">
                                                                <div>
                                                                    <img src="{{$imgPath}}" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
                                                                    <div class="text-muted small text-nowrap mt-2">{{$msg->created_at}}</div>
                                                                </div>
                                                                <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                                                                    <div class="font-weight-bold mb-1">{{$specialistSender ? $msg->group->customer->name : $msg->group->specialist->name  }}</div>
                                                                    {{$msg->message}}
                                                                </div>
                                                            </div>
                                                        @endif


                                                @endforeach
                                            </div>

                                        </div>
                                        {!! $messages->links() !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>

                @else
                    <h1><strong>მიმოწერა ცარიელია</strong></h1>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>


    <div class="col-4">
        <div class="card"   >
            <div class="card-header">
                <h3 class="card-title">გადახდები</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body" style="height: 80vh; overflow-x: auto">

                @if(!$payments->isEmpty())
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>თარიღი</th>
                            <th>თანხა</th>
                            <th>სტატუსი</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $p)
                            <tr>
                                <td>{{$p->created_at}}</td>
                                <td>{{$p->amount}}</td>
                                <td>{{$p->status}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @else
                    <h1><strong>გადახდები არ არის</strong></h1>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection
