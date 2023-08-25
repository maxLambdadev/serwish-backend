@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">სერვისები</li>
    <li class="breadcrumb-item"><a href="{{route('manager.services.category.index')}}">სპეციალობა</a></li>
    <li class="breadcrumb-item active">სპეციალობისის ფორმა</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.services.category.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.services.category.index')}}">

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
                                             extraClasses="slug-from-input"
                               />

                               <x-form.input name="meta_title"
                                             title="Meta title"
                                             placeholder="შეიყვანეთ მეტა სათაური"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="meta_title"
                                             languageSwitcher="true"
                                             extraClasses="slug-from-input"
                               />

                               <x-form.input name="meta_description"
                                             title="Meta Description"
                                             placeholder="შეიყვანეთ მეტა აღწერა "
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="meta_description"
                                             languageSwitcher="true"
                                             extraClasses="slug-from-input"
                               />


                               <x-form.textarea name="description"
                                                :title="__('Blog Description')"
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

                           <div class="form-group">
                               <label for="sort_order">სორტირება ( 1 დაბალი  )</label>
                               <input type="text" name="sort_order"
                                      value="{{ isset($entity) && $entity->sort_order !== null ? $entity->sort_order : "0" }}"
                                      class="form-control"/>
                           </div>
                           <div class="form-group">
                               <label for="sort_order"> ბლოგის პოზიცია</label>
                               <input type="text" name="blog_position"
                                      value="{{ isset($entity) && $entity->blog_position !== null ? $entity->blog_position : "0" }}"
                                      class="form-control"/>
                           </div>


                           <div class="form-group">
                               <div class="form-group">
                                   <label>აირჩიეთ ტეგები </label>
                                   <div class="select2-purple">
                                       <select name="tag_id[]" class="select2"  multiple data-placeholder="აირჩიეთ ტეგებo" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                           @foreach($tags as $t)
                                               <option value="{{$t->name}}"
                                                       @if(isset($entity) && $entity->tags->where('id',$t->id)->first() !== null )
                                                           selected="selected"
                                                   @endif
                                               >
                                                   {{$t->name}}
                                               </option>
                                           @endforeach
                                       </select>
                                   </div>
                               </div>


                               <div class="form-group">
                               <div class="form-group">
                                   <label>მშობელი კატეგორია</label>
                                   <div class="select2-purple">
                                       <select name="category_id" class="select2"  data-placeholder="აირჩიეთ მშობელი კატეგორია" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                           <option value="">აირჩიეთ მშობელი</option>
                                       @foreach($categories as $category)
                                               <option value="{{$category->id}}"
                                                       @if(isset($entity) && $entity->parent != null && $entity->parent->id == $category->id ) selected="selected" @endif
                                               >{{$category->title}}</option>
                                           @endforeach
                                       </select>
                                   </div>
                               </div>

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
        $('.select2').select2({
            theme: 'bootstrap4',
            tags: true
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
