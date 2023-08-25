@extends('manager.layouts.auth')

@section('login')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Ser</b>WISH</a>
        </div>
        <!-- /.login-logo -->

        <div class="card">
            <div class="card-body login-card-body ">
                <p class="login-box-msg">გაიარეთ ავტორიზაცია</p>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5>დაფიქსირდა შეცდომა</h5>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('loginWithCode')}}" method="post">
                    @csrf
                    <input type="hidden" name="user" value="{{$user->id}}">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="SMS კოდი" name="code">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">ავტორიზაცია</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


            </div>
            <!-- /.login-card-body -->
        </div>
    </div>


@endsection

