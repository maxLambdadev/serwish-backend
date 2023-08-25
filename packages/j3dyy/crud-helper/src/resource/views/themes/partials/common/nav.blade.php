<!-- Brand Logo -->
<a href="index3.html" class="brand-link">
    <img src="{{asset('/themes/martve/img/logo.png')}}" alt="Martve Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light ">Martve v0.1</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{asset('manager/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{route('manager.dashboard')}}" class="nav-link {{ request()->routeIs('manager.dashboard*') ? 'active' : ''  }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>




            <li class="nav-header">მართვა</li>

            <li class="nav-item">
                <a href="" class="nav-link">
                    <i class="nav-icon fas fa-futbol"></i>
                    <p>
                        ლიგა
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('manager.league.teams.index')}}" class="nav-link {{ request()->routeIs('manager.teams*') ? 'active' : ''  }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                გუნდები
                            </p>
                        </a>
                    </li>
                </ul>
            </li>




            <li class="nav-item {{ request()->routeIs('manager.users*','manager.roles*') ? 'menu-is-opening menu-open active' : ''  }}">

                <a href="#" class="nav-link {{ request()->routeIs('manager.users*','manager.roles*') ? 'active' : ''  }}">
                    <i class="nav-icon fas fa-edit"></i>
                    <p>
                        მომხმარებლები
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="{{route('manager.users.index')}}" class="nav-link {{ request()->routeIs('manager.users*') ? 'active' : ''  }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                მომხმარებლები
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('manager.roles.index')}}" class="nav-link {{ request()->routeIs('manager.roles*') ? 'active' : ''  }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                უფლებები
                            </p>
                        </a>
                    </li>

                </ul>
            </li>





        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
