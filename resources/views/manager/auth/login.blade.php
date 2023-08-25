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
                <form action="{{route('processLogin')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="tel" class="form-control" placeholder="ტელეფონის ნომერი" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="პაროლი" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    დამიმახსოვრე
                                </label>
                            </div>
                        </div>
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

