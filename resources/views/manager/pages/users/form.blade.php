@extends('manager.layouts.app')
@section('section-title', 'მოხმარებელი')

@section('content')

    <div class="container-fluid">
        <form id="quickForm" action="{{route('manager.users.store')}}">

       <div class="row">
           <div class="col-md-9">

               <!-- jquery validation -->
               <div class="card card-primary">
                   <div class="card-header">
                       <h3 class="card-title">მომხმარებლის რედაქტირება </h3>
                   </div>
                   <!-- /.card-header -->
                   <!-- form start -->
                   @csrf

                   @isset($entity)
                       <input type="hidden" name="id" value="{{$entity->id}}">
                   @endif

                   <div class="card-body">

                       <x-form.input name="email"
                                     title="ელ.ფოსტა"
                                     placeholder="შეიყვანეთ ელ.ფოსტა"
                                     :entity="isset($entity) ? $entity : null"
                                     entityKey="email"
                       />
                       <x-form.input name="name"
                                     title="სახელი"
                                     placeholder="შეიყვანეთ სრული სახელი"
                                     :entity="isset($entity) ? $entity : null"
                                     entityKey="name"
                       />


                       {{--                    @if(!isset($entity))--}}
                       <x-form.input name="password"
                                     type="password"
                                     title="პაროლი"
                                     placeholder="შეიყვანეთ პაროლი"
                       />

                       <x-form.input name="password_confirmation"
                                     type="password"
                                     title="გაიმეორეთ პაროლი"
                                     placeholder="გაიმეორეთ პაროლი"
                       />


                       <div class="form-group">
                           <label for="page">უფლება</label>
                           <select name="role_id" class="form-control">
                               <option>აირჩიეთ უფლება</option>
                               @foreach($roles as $role)
                                   <option value="{{$role->id}}" {!! isset($entity) && $entity->hasRole($role->name) ? 'selected' : '' !!}>
                                       {{$role->name}}
                                   </option>

                               @endforeach
{{--                               <option value="2" {!! isset($entity) && $entity->hasRole('client') ? 'selected' : '' !!}>კლიენტი</option>--}}
                           </select>
                       </div>

                   </div>
                   <!-- /.card-body -->
                   <div class="card-footer">
                       <button type="submit" class="btn btn-primary">შენახვა</button>
                       <button type="reset" class="btn btn-danger">გასუფთავება</button>
                   </div>
               </div>
               <!-- /.card -->
           </div>

           <div class="col-md-3">
               <div class="card card-primary">
                   <div class="card-header">
                       <h3 class="card-title">დამატებითი იფორმაცია </h3>
                   </div>
                   <!-- /.card-header -->
                   <!-- form start -->
                   <div class="card-body">


                       <div class="form-group">
                           <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                               <input  type="checkbox" class="custom-control-input makeSpecialist" name="client_type" id="customSwitch" {!! isset($entity) && $entity->client_type == 'employee'  ? 'checked="checked"' : ''  !!} >
                               <label class="custom-control-label user-type-text" for="customSwitch">{!!isset($entity) && $entity->client_type == 'employee' ? 'სპეციალისტი' : 'მომხმარებელი'  !!}</label>
                           </div>
                       </div>
                       <div class=" form-group">
                           <div class="col-md-12">
                               <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                   <input  type="checkbox" class="custom-control-input makePersonal" name="personal" id="personalSwitch" {!! isset($entity) && $entity->personal == 'personal'  ? 'checked="checked"' : ''  !!} >
                                   <label class="custom-control-label user-type-text-personal" for="personalSwitch">{!!isset($entity) && $entity->personal == 'personal' ? 'ფიზიკური პირი' : 'კომპანია'  !!}</label>
                               </div>
                           </div>
                       </div>

                       <div class="form-group">
                          <div class="col-md-12">
                              <label for="id_number" class="personal_name">{!!isset($entity) && $entity->personal == 'personal' ? 'პირადი ნომერი' : 'საიდენტიფიკაციო ნომერი'  !!}</label>
                              <input type="text" name="id_number" class="form-control input-picker" id="id_number" placeholder="პირადი ნომერი" value="{!! !isset($entity) ? '': $entity->id_number !!}" aria-invalid="false">

                          </div>
                       </div>

                       <div class=" form-group">
                           <div class="col-md-12">
                               <label for="id_number">დაბადების თარიღი</label>
                               <input type="date" name="date_of_birth" class="form-control input-picker" id="id_number" placeholder="დაბადების თარიღი" value="{!! isset($entity) && $entity->date_of_birth != null ? \Carbon\Carbon::parse($entity->date_of_birth)->format('Y-m-d') : '' !!}" aria-invalid="false">
                           </div>
                       </div>


                       <div class=" form-group">
                           <div class="col-md-12">
                               <label for="id_number">ტელეფონის ნომერი</label>
                               <input type="tel" name="phone_number" class="form-control input-picker" id="id_number" placeholder="ტელეფონის ნომერი" value="{!! isset($entity)  ? $entity->phone_number : '' !!}" aria-invalid="false">
                           </div>
                       </div>

                   </div>

               </div>
           </div>
       </div>
        </form>

    </div>


@endsection

@push('script')

    <script>
        $(function () {
            validate(
                {
                    email: {
                        required: true,
                        email: true,
                    },
                    name: {
                        required: true,
                    }
                },
                {
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a vaild email address"
                    },
                    name: {
                        required: "Please provide a password"
                    }
                },

            )


            $('#quickForm').submit(function (e){

                e.preventDefault()
                post(
                    $(this).attr('action'),
                    $(this).serialize(),
                    formSuccessHandler,
                    formErrorHandler
                )
            })


            $('.makeSpecialist').change(function () {
                let isBasicUser = $(this).prop('checked')
                let userType = "მომხმარებელი";
                if (isBasicUser) {
                    userType = "სპეციალისტი"
                }
                $('.user-type-text').html(userType)
            });

            $('.makePersonal').change(function () {
                let isBasicUser = $(this).prop('checked')
                let userType = "კომპანია";
                let idText = "საიდენტიფიკაციო ნომერი"

                if (isBasicUser) {
                    idText = "პირადი ნომერი";
                    userType = "ფიზიკური  პირი"
                }
                $('.personal_name').html(idText)
                $('.user-type-text-personal').html(userType)
            });

        });
    </script>

@endpush
