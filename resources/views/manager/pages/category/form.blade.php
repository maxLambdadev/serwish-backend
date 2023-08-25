@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item"><a href="{{route('manager.blog.category.index')}}">კატეგორია</a></li>
    <li class="breadcrumb-item active">კატეგორიის ფორმა</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.blog.category.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.blog.category.index')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-9">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">კატეგორიის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="title"
                                             :title="__('title')"
                                             placeholder="შეიყვანეთ სათაური"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="title"
                                             languageSwitcher="true"
                                             extraClasses="slug-from-input"
                               />
                               <x-form.textarea name="description"
                                                :title="__('title')"
                                                :entity="isset($entity) ? $entity : null"
                                                entityKey="description"
                                                languageSwitcher="true"
                               />
                               <x-form.slug name="slug"
                                             :title="__('slug')"
                                             placeholder=""
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="slug"
                                             languageSwitcher="true"
                                             extraClasses="slug-input"
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
                           <x-form.switcher name="isActive"
                                         :title="__('isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="isActive"
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
