@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item"><a href="{{route('manager.services.service.index')}}">სერვისი</a></li>
    <li class="breadcrumb-item"><a href="{{route('manager.services.service.index')}}">მომსახურებები</a></li>

    <li class="breadcrumb-item active">დეტალურად</li>
@endpush
@section('section-title', 'სერვისი')

@section('content')
    <form class="col-12 ajax-form" action="{{route('manager.services.service.store')}}" method="post" >
        @if(request()->get('pongBack'))
            <input type="hidden" name="pongBack" value="{{request()->get('pongBack')}}">
        @endif
        @csrf
        @isset($entity)
            <input type="hidden" name="id" value="{{$entity->id}}">
        @endif
        <input type="hidden" name="redirect" value="{{route('manager.services.service.index')}}">

        <div class="row">
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
                               <input type="text" class="knob" data-readonly="true" value="{{$entity->viewCount == null ? 0 : $entity->viewCount}}" data-width="60" data-height="60" data-fgColor="#39CCCC" disabled>
                               <div class="text-white"> ნახვა</div>
                           </div>
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
                   <div class="card-header">
                       <h3 class="card-title">მომსახურება დეტალურად</h3>

                   </div>
                   <div class="card-body">
                       <div class="row">
                           <div class="col-12 d-flex justify-content-between" style="padding: 10px">
                               @php($next = \App\Models\Services::where('id', '>', $entity->id)->orderBy('id','asc')->first())
                               @php($previous = \App\Models\Services::where('id', '<', $entity->id)->orderBy('id','desc')->first())


                               @if($previous != null )
                                   <a  class="btn btn-primary" href="{{ route('manager.services.service.show', [ 'id' => $previous->id ])  }}"> < უკან </a>
                               @endif

                               @if($entity->review_status !== 'published')
                                   <a href="{{ route('manager.services.service.delete', [ 'id' => $entity->id , 'pongBack'=>request()->get('pongBack')]) }}" class="btn btn-danger">
                                       წაშლა
                                   </a>
                               @endif

                               @if($next != null)
                                   <a href="{{ route('manager.services.service.show', [ 'id' => $next->id ]) }}" class="btn btn-primary"> შემდეგი ></a>
                               @endif

                           </div>
                       </div>


                       <div class="row">
                           <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
                               <div class="row">
                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">ფასი</span>
                                               <span class="info-box-number text-center text-muted mb-0">{{number_format($entity->price,2)}}</span>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">შემსრულებელი</span>
                                               <span class="info-box-number text-center text-muted mb-0">
                                            <a href="{{route('manager.users.show',['id'=>$entity->specialist->id])}}">{{$entity->specialist->name}}</a>
                                        </span>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="col-12 col-sm-4">
                                       <div class="info-box bg-light">
                                           <div class="info-box-content">
                                               <span class="info-box-text text-center text-muted">შეფასება</span>
                                               <span class="info-box-number text-center text-muted mb-0">5.4</span>
                                           </div>
                                       </div>
                                   </div>

                               </div>

                               <div class="card-body">

                                   <x-form.input name="contact_number"
                                                 title="საკონტაქტო ნომერი (თუ ცარიელია ნომერი წავა პროფილიდან)"
                                                 placeholder="შეიყვანეთ საკონტაქტო ნომერი"
                                                 :entity="isset($entity) ? $entity : null"
                                                 entityKey="contact_number"
                                                 extraClasses="slug-from-input"
                                   />

                                   <div class="row col-md-12" style="padding-left: 0; padding-right: 0" >

                                       <div class="col-md-8">
                                           <x-form.input name="price"
                                                         title="ფასი"
                                                         placeholder="შეიყვანეთ ფასი"
                                                         :entity="isset($entity) ? $entity : null"
                                                         entityKey="price"
                                                         extraClasses="slug-from-input"
                                           />
                                       </div>

                                       <div class="col-md-4 form-group">
                                           <label> ფასის ტიპი</label>
                                           <div class="select2-purple">
                                               <select name="price_type" class=" form-control" style="width: 100%;">
                                                   <option value="hour" {!! isset($entity) && $entity->price_type  == 'hour' ? 'selected' : '' !!}>საათში</option>
                                                   <option value="day" {!! isset($entity) && $entity->price_type  == 'day' ? 'selected' : '' !!}>დღეში</option>
                                                   <option value="week" {!! isset($entity) && $entity->price_type  == 'week' ? 'selected' : '' !!}>კვირაში</option>
                                                   <option value="month" {!! isset($entity) && $entity->price_type  == 'month' ? 'selected' : '' !!}>თვეში</option>
                                                   <option value="end" {!! isset($entity) && $entity->price_type  == 'end' ? 'selected' : '' !!}>დასრულებისას</option>
                                               </select>
                                           </div>
                                       </div>


                                   </div>

                                   <x-form.input name="title"
                                                 title="სათაური"
                                                 placeholder="შეიყვანეთ სათაური"
                                                 :entity="isset($entity) ? $entity : null"
                                                 entityKey="title"
                                                 languageSwitcher="true"
                                                 extraClasses="slug-from-input"
                                   />

                                   <x-form.textarea name="description"
                                                    :title="__('description')"
                                                    :entity="isset($entity) ? $entity : null"
                                                    entityKey="description"
                                                    languageSwitcher="true"
                                   />


                                   <div class="form-group">
                                       <input type="checkbox"
                                              id="has_online_payment" name="has_online_payment"
                                              data-bootstrap-switch
                                              data-off-color="danger"
                                              data-on-color="success"
                                              @if($entity != null  && $entity->has_online_payment) checked  @endif
                                       >

                                       <label for="has_online_payment">აქვს ონლაინ გადახდა</label>
                                   </div>

                                   <div class="form-group">
                                       <input type="checkbox"
                                              id="priority" name="priority"
                                              data-bootstrap-switch
                                              data-off-color="danger"
                                              data-on-color="success"
                                              @if($entity != null  && $entity->priority) checked  @endif
                                       >

                                       <label for="has_online_payment">პრიორიტეტული</label>
                                   </div>


                                   <div class="form-group">
                                       <input type="checkbox"
                                              id="has_serwish_quality" name="has_serwish_quality"
                                              data-bootstrap-switch
                                              data-off-color="danger"
                                              data-on-color="success"
                                              @if($entity != null  && $entity->has_serwish_quality) checked  @endif
                                       >

                                       <label for="has_serwish_quality">SERWISH ხარისხი</label>
                                   </div>

                                   <div class="form-group">
                                       <input type="checkbox"
                                              id="is_active" name="is_active"
                                              data-bootstrap-switch
                                              data-off-color="danger"
                                              data-on-color="success"
                                              @if($entity != null  && $entity->is_active) checked  @endif
                                       >

                                       <label for="has_serwish_quality">აქტიური</label>
                                   </div>




                                   <x-form.dropdown name="category"
                                                    :entity="isset($entity) ? $entity : null"
                                                    title="აირჩიეთ კატეგორია"
                                                    :list="$categories"
                                                    defaultValue="აირჩიეთ კატეგორია"
                                                    value="id"
                                                    displayName="title"
                                                    related="categories"

                                   />

                                   <x-form.dropdown name="tags"
                                                    :entity="isset($entity) ? $entity : null"
                                                    title="აირჩიეთ ტეგები"
                                                    :list="$tags"
                                                    defaultValue="აირჩიეთ ტეგი"
                                                    value="id"
                                                    displayName="name"
                                                    related="tags"
                                                    tags="true"
                                   />

                                   <x-form.dropdown name="cities"
                                                    :entity="isset($entity) ? $entity : null"
                                                    title="აირჩიეთ ქალაქები"
                                                    :list="$cities"
                                                    defaultValue="აირჩიეთ ქალაქი"
                                                    value="id"
                                                    displayName="name"
                                                    related="cities"

                                   />

                               </div>

                               <div class="card-footer">
{{--                                   @if(isset($entity) && ($entity->reviewer_id == null || $entity->reviewer_id == \Illuminate\Support\Facades\Auth::user()->id))--}}
                                       <button type="submit" class="btn btn-success">შენახვა</button>
                                       <button type="reset" class="btn btn-danger">გასუფთავება</button>
{{--                                   @else--}}
{{--                                       სერვისი უკვე განხილვის პროცესშია სხვა ადმინის მიერ--}}
{{--                                   @endif--}}
                               </div>
                           </div>
                       </div>
                   </div>
                   <!-- /.card-body -->
               </div>
           </div>

           <div class="col-md-3">
               <div class="card">
                   <div class="card-header">
                       <h3 class="card-title">სტატუსი</h3>

                   </div>
                   <div class="card-body">


                       <div class="form-group">
                           <div class="form-group">
                               <label> განხილვის სტატუსი</label>
                               <div class="select2-purple">
                                   @if(isset($entity) && $entity->reviewer_id !== null)
                                       ადმინისტრატორი: <a href="{{route('manager.users.show',['id'=>$entity->reviewer->id])}}">{{$entity->reviewer->name}}</a>
                                       <br>
                                   @endif
                                   <select name="review_status" class="select2"  data-placeholder="აირჩიეთ მშობელი კატეგორია" data-dropdown-css-class="select2-purple" style="width: 100%;"
                                           !
                                       {!! isset($entity) && $entity->reviewer_id  !== \Illuminate\Support\Facades\Auth::user()->id && $entity->reviewer_id !== null ? 'disabled' : '' !!}
                                   >
                                       <option value="started" {!! isset($entity) && $entity->review_status  == 'started' ? 'selected' : '' !!}>განსახილველი</option>
                                       <option value="in_review" {!! isset($entity) && $entity->review_status == 'in_review' ? 'selected' : '' !!}>განხილვის პროცესში</option>
                                       <option value="published" {!! isset($entity) && $entity->review_status == 'published' ? 'selected' : '' !!}>შემოწმებული</option>
                                   </select>
                               </div>
                           </div>

                           <div class="form-group">
                               <label> VIP პაკეტი </label>
                               <div class="select2-purple">
                                   <select name="packet_id" class="select2"  data-placeholder="აირჩიეთ მშობელი კატეგორია" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                       <option value="none">აირჩიეთ პაკეტი</option>
                                       @foreach(\App\Models\PayablePacket::all() as $packet)
                                           <option value="{{$packet->id}}"
                                           @if(isset($entity) && $entity->packet_id == $packet->id)
                                               selected="selected"
                                           @endif
                                           >{{$packet->name}}</option>
                                       @endforeach
                                   </select>
                               </div>
                           </div>

                       </div>

                       <x-form.dropzone name="files"
                                        :title="__('isActive')"
                                        :entity="isset($entity) ? $entity : null"
                                        :removeEntity="\App\Models\Services::class"
                                        :removeRoute="isset($entity) ? route('manager.media.detach', ['id'=>$entity->id]) : null"
                       />

                       <!-- /.card-body -->
                   </div>


               </div>

           </div>

       </div>
    </form>

@endsection

@push('script')
    <script src="{{asset('manager/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <script src="{{asset('manager/plugins/filterizr/jquery.filterizr.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
            $('.filter-container').filterizr({gutterPixels: 3});

            $('.ajax-form').submit(function (e){
                e.preventDefault()
                post(
                    $(this).attr('action'),
                    $(this).serialize(),
                    formSuccessHandler,
                    formErrorHandler
                )
            })

        })
    </script>
@endpush

