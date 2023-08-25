@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item"><a href="{{route('manager.ads.list')}}">ბანერების მენეჯერი</a></li>
    <li class="breadcrumb-item active">ბანერის ფორმა</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.ads.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.ads.list')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-9">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">ბანერის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="name"
                                             :title="__('title')"
                                             placeholder="შეიყვანეთ სათაური"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="name"
                               />

                               <x-form.input name="link"
                                             title="ბმული"
                                             placeholder="შეიყვანეთ ბმული"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="link"
                               />

                               <x-form.input name="order"
                                             title="პოზიცია"
                                             placeholder="შეიყვანეთ პოზიცია მაგ: 1"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="order"
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
                               <label for="page">გვერდი</label>
                               <select name="page" class="form-control">
                                   <option value="home" {!! isset($entity) && $entity->page == 'home' ? 'selected' : '' !!}>მთავარი გვერდი</option>
                                   <option value="services" {!! isset($entity) && $entity->page == 'services' ? 'selected' : '' !!}>სერვისები</option>
                                   <option value="specialists" {!! isset($entity) && $entity->page == 'specialists' ? 'selected' : '' !!}>სპეციალისტები</option>
                                   <option value="categories" {!! isset($entity) && $entity->page == 'categories' ? 'selected' : '' !!}>კატეგორია</option>
                                   <option value="posts" {!! isset($entity) && $entity->page == 'posts' ? 'selected' : '' !!}>პოსტები</option>
                               </select>
                           </div>


                           <x-form.switcher name="is_active"
                                         :title="__('isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="is_active"
                           />

                           <x-form.dropzone name="isActive"
                                            :multiple="false"
                                            :title="__('isActive')"
                                            :entity="isset($entity) ? $entity : null"
                                            :removeEntity="\App\Models\Ads::class"
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
