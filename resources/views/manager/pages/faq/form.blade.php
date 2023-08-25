@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">მართვა</li>
    <li class="breadcrumb-item active">როგორ მუშაობს</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.faq.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.faq.list')}}">

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
                                             title="სპეციალობა"
                                             placeholder="შეიყვანეთ სპეციალობა"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="title"
                                             languageSwitcher="true"
                               />
                               <x-form.textarea name="description"
                                                :title="__('title')"
                                                :entity="isset($entity) ? $entity : null"
                                                entityKey="description"
                                                languageSwitcher="true"
                               />
                               <x-form.slug name="button_link"
                                             title="ბმული"
                                             placeholder=""
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="button_link"
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

                           <x-form.switcher name="is_active"
                                         :title="__('isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="is_active"
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
