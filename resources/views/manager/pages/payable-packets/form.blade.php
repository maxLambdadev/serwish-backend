@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item"><a href="{{route('manager.payable-packet.list')}}">პაკეტები</a></li>
    <li class="breadcrumb-item active">ფასიანი პაკეტის ფორმა</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.payable-packet.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.payable-packet.list')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-9">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">ფასიანი პაკეტის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="name"
                                             title="სათაური"
                                             placeholder="შეიყვანეთ პაკეტის სახელი მაგალითად: VIP"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="title"
                                             languageSwitcher="true"
                                             extraClasses="slug-from-input"
                               />

                               <x-form.textarea name="description"
                                                title="აღწერა"
                                                :entity="isset($entity) ? $entity : null"
                                                entityKey="description"
                                                languageSwitcher="true"
                               />


                           </div>
                           <!-- /.card-body -->
                           <div class="card-footer">
                               <button type="submit" class="btn btn-success">შენახვა</button>
                               <button type="reset" class="btn btn-danger">გასუფთავება</button>
                           </div>
                   </div>
               </div>

               <div class="col-md-3">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">Meta Panel </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                       <div class="card-body">

                           <div class="form-group">
                               <label for="sort_order">პაკეტის ხანგრძლივობა ( დღეებში ) </label>
                               <input type="number" name="valid_days"
                                      value="{{ isset($entity) && $entity->valid_days !== null ? $entity->valid_days : "1" }}"
                                      class="form-control"/>
                           </div>


                           <div class="form-group">
                               <label for="sort_order">პრიორიტეტი ( 1 დაბალი )</label>
                               <input type="number" name="priority"
                                      value="{{ isset($entity) && $entity->priority !== null ? $entity->priority : "0" }}"
                                      class="form-control"/>
                           </div>

                           <div class="form-group">
                               <label for="sort_order">ღირებულება</label>
                               <input type="number" name="price"
                                      value="{{ isset($entity) && $entity->price !== null ? $entity->price : "0" }}"
                                      class="form-control"
                                      min="0" max="10000" step="0.5"
                               />
                           </div>



                           <x-form.switcher name="isActive"
                                         :title="__('isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="isActive"
                           />

                           <x-form.dropzone name="mm"
                                            :multiple="false"
                                            :title="__('isActive')"
                                            :entity="isset($entity) ? $entity : null"
                                            :removeEntity="\App\Models\Blog\Category::class"
                                            :removeRoute="isset($entity) ? route('manager.media.detach', ['id'=>$entity->id]) : null"
                           />
                       </div>

               </div>
           </div>
        <!-- /.card -->
        </form>

    </div>


@endsection

@push('script')
    <script>
        $(function () {
            $('#quickForm').submit(function (e){
                e.preventDefault()
                post(
                    $(this).attr('action'),
                    $(this).serialize(),
                    formSuccessHandler,
                    formErrorHandler
                )
            })
        });
    </script>
@endpush
