
@extends('manager.layouts.app')
@section('section-title', 'სამართავი პანელი')
@section('content')

{{--    @php--}}
{{--        $userPermissions = auth()->user()->roles()->first()->permissions()->withPivot('can_add','can_edit','can_delete')->get()--}}
{{--    @endphp--}}
{{--    {!! dd( $userPermissions->where('name','=','service')->first()->pivot->can_edit ) !!}--}}


    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">{{\App\Models\Services::count()}} სერვისი</span>

                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70%-ით მოიმატა 30- დღეში
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="ion ion-bag"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">2 შეკვეთა</span>

                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70%-ით მოიმატა 30- დღეში
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info">
            <span class="info-box-icon">  <i class="ion ion-person-add"></i></span>

            <div class="info-box-content">
                <span class="info-box-text"> {{\App\Models\User::count()}} სპეციალისტი</span>

                <div class="progress">
                    <div class="progress-bar" ></div>
                </div>
                <span class="progress-description">
                  2 ახალი სპეციალობა ბოლო 7 დღეში
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info">
            <span class="info-box-icon">  <i class="ion ion-person-add"></i></span>

            <div class="info-box-content">
                <span class="info-box-text"> {{\App\Models\User::count()}} მომხმარებელი</span>

                <div class="progress">
                    <div class="progress-bar" style="width: 10%"></div>
                </div>
                <span class="progress-description">
                    აქედან 70 % სპეციალისტი
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>



    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ახალი მომხმარებლები</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>სახელი</th>
                        <th>ელ.ფოსტა</th>
                        <th style="width: 30px;">სპეციალისტი</th>
                        <th style="width: 30px;">ფიზიკური პირი</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{$u->id}}</td>
                            <td>{{$u->name}}</td>
                            <td>{{$u->email}}</td>
                            <td>
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch{{$u->id}}" {!!$u->client_type == 'employee'  ? 'checked="checked"' : ''  !!} disabled>
                                        <label class="custom-control-label" for="customSwitch{{$u->id}}">{!!$u->client_type == 'employee' ? 'სპეციალისტი' : 'მომხმარებელი'  !!}</label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-success custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch{{$u->id}}" {!!$u->personal == 'personal'  ? 'checked="checked"' : ''  !!} disabled>
                                        <label class="custom-control-label" for="customSwitch{{$u->id}}">{!!$u->personal == 'personal' ? 'ფიზიკური' : 'იურიდიული'  !!}</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </div>


@endsection
