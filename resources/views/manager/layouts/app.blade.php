@include('manager.partials.common.header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{asset('manager/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>
    <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="toggle-sidebar-nav"><i class="fas fa-bars"></i></a>

    <!-- Navbar -->
{{--    @include('manager.partials.common.topBar')--}}
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('manager.partials.common.nav')
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
{{--        @include('manager.partials.breadcrumb')--}}

        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @yield('panel')

                    @yield('content')
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>

    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2022-2022 <a href="#">SerWISH</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>

@stack('footer')
</div>
<!-- ./wrapper -->

@include('manager.partials.common.footer')


</body>
</html>
