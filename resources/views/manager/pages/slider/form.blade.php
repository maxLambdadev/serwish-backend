@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">მართვა</li>
    <li class="breadcrumb-item">სლაიდერი</li>
    <li class="breadcrumb-item active">დამატება</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.slider.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.slider.list')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-9">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">სპეციალობისის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="title"
                                             title="სათაური"
                                             placeholder="შეიყვანეთ სათაური"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="title"
                                             languageSwitcher="true"
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


                               <div class="form-group showMoreValue">
                                   <label for="showMoreBtnshowMoreValue"> ღილაკის ბმული </label>

                                   <input type="text"
                                          id="showMoreBtnshowMoreValue" name="showMoreBtn"
                                          class="form-control"
                                          placeholder="შეიყვანეთ ბმული"
                                          @isset($entity)
                                              value="{{$entity->showMoreBtn}}"
                                          @endif
                                   >
                               </div>


                               <x-form.switcher name="isActive"
                                         :title="__('isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="isActive"
                           />

                           <x-form.input name="sort_order"
                                         title="პოზიცია"
                                         placeholder="შეიყვანეთ პოზიცია მაგ: 1"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="sort_order"
                           />

                           <x-form.dropzone name="isActive"
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
        $('.select2').select2({
            theme: 'bootstrap4'
        })

        $(function () {

            $('#showMoreValue').change(function (){

                console.log($(this).attr('value'))

            })

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
