@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">ბლოგი</li>
    <li class="breadcrumb-item">კონფიგურაცია</li>
    <li class="breadcrumb-item active">ლოკალიზაცია</li>

@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.configuration.locales.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.configuration.locales.index')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-9">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">ლოკალიზაციის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="name"
                                             :title="__('panel.name')"
                                             placeholder="შეიყვანეთ სახელი"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="name"
                               />
                               <x-form.input name="original_name"
                                             :title="__('panel.original_name')"
                                             placeholder="საერთაშორისო სახელი"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="original_name"
                               />
                               <x-form.input name="iso_code"
                                             :title="__('panel.iso_code')"
                                             placeholder="ISO კოდი"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="iso_code"
                               />


                           </div>
                           <!-- /.card-body -->
                           <div class="card-footer">
                               <button type="submit" class="btn btn-success">შენახვა</button>
                           </div>
                   </div>
               </div>

               <div class="col-md-3">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">მეტა პარამეტრები </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                       <div class="card-body">


                           <x-form.switcher name="is_active"
                                         :title="__('panel.isActive')"
                                         :entity="isset($entity) ? $entity : null"
                                         entityKey="is_active"
                           />

                           <x-form.switcher name="is_default"
                                            :title="__('panel.isDefault')"
                                            :entity="isset($entity) ? $entity : null"
                                            entityKey="is_default"
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
