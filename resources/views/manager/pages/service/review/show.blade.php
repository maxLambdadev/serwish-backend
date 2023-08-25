@extends('manager.layouts.app')


@section('section-title', 'სერვისი')

@section('content')
    <form class="col-12 ajax-form" action="" method="post" >
        @csrf
        @isset($entity)
            <input type="hidden" name="id" value="{{$entity->id}}">
        @endif
        <input type="hidden" name="redirect" value="{{route('manager.services.service.index')}}">

        <div class="row">
           <div class="col-md-12">


               <div class="card">
                   <div class="card-header">
                       <h3 class="card-title">შეფასება</h3>

                   </div>
                   <div class="card-body">
                       <div class="row">
                           <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
                               <div class="row">
                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">სერვისი</span>
                                               <span class="info-box-number text-center text-muted mb-0">
                                                    <a href="{{route('manager.services.service.show',['id'=>$entity->service->id])}}">{{$entity->service->title}}</a>
                                               </span>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">სპეციალისტი</span>
                                               <span class="info-box-number text-center text-muted mb-0">
                                            <a href="{{route('manager.users.show',['id'=>$entity->service->specialist->id])}}">{{$entity->service->specialist->name}}</a>
                                        </span>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">შეფასება</span>
                                               <span class="info-box-number text-center text-muted mb-0">
                                                   {!!  $entity->likes ? '<i style="color:green" class="fa fa-thumbs-up" aria-hidden="true"></i>' : '<i style="color:red" class="fa fa-thumbs-down" aria-hidden="true"></i>' !!}
                                               </span>
                                           </div>
                                       </div>
                                   </div>

                               </div>

                               <div class="card-body">



                                   <div class="row col-md-12" style="padding-left: 0; padding-right: 0" >

                                        <div class="form-group">
                                            <label for="reviw">შეფასება</label>
                                            <p class="">
                                                {!! $entity->description !!}
                                            </p>
                                        </div>

                                   </div>

                               </div>


                           </div>
                       </div>
                   </div>
                   <!-- /.card-body -->
               </div>
           </div>



       </div>
    </form>

@endsection



