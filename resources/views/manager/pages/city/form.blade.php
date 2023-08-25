@extends('manager.layouts.app')

@push('crumbs')
    <li class="breadcrumb-item">მართვა</li>
    <li class="breadcrumb-item"><a href="{{route('manager.city.list')}}">ქალაქები</a></li>

    <li class="breadcrumb-item active">ქალაქის ფორმა</li>
@endpush
@section('content')

    <div class="col-md-12">
        <form id="quickForm" action="{{route('manager.city.store')}}">
            <input type="hidden" name="redirect" value="{{route('manager.city.list')}}">

        <!-- jquery validation -->

           <div class="row">
               <div class="col-md-12">
                   <div class="card card-primary">
                       <div class="card-header">
                           <h3 class="card-title">სიახლის ფორმა </h3>
                       </div>
                       <!-- /.card-header -->
                       <!-- form start -->
                           @csrf
                           @isset($entity)
                               <input type="hidden" name="id" value="{{$entity->id}}">
                           @endif
                           <div class="card-body">

                               <x-form.input name="name"
                                             title="დასახელება"
                                             placeholder="შეიყვანეთ სათაური"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="name"
                                             languageSwitcher="true"
                               />
                               <x-form.input name="position"
                                             title="პოზიცია"
                                             placeholder="შეიყვანეთ პოზიცია"
                                             :entity="isset($entity) ? $entity : null"
                                             entityKey="name"
                               />
                               <x-form.switcher name="isActive"
                                                :title="__('isActive')"
                                                :entity="isset($entity) ? $entity : null"
                                                entityKey="is_active"
                               />
                           </div>
                           <!-- /.card-body -->
                           <div class="card-footer">
                               <button type="submit" class="btn btn-success">შენახვა</button>
                               <button type="reset" class="btn btn-danger">გასუფთავება</button>
                           </div>
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
