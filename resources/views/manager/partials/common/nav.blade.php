
@php
    $userPermissions = auth()->user()->roles()->first()->permissions()->withPivot('can_add','can_edit','can_delete')->get()
@endphp
<!-- Brand Logo -->
<a href="{{route('manager.dashboard')}}" class="brand-link">
    <span class="brand-text font-weight-light ">Serwish</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{route('manager.dashboard')}}" class="nav-link {{ request()->routeIs('manager.dashboard*') ? 'active' : ''  }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        {{__('panel.home')}}
                    </p>
                </a>
            </li>

            @php($perm =  $userPermissions->where('name','=','service')->first() )
            @if( auth()->user()->hasRole('administrator') ||
                            ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                      )
                <li class="nav-item ">
                    <a href="{{route('manager.services.service.my-approvals')}}" class="nav-link {{ request()->is('panel/services/service/my-approvals')  ? 'active' : ''}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                           დადასტურებული
                        </p>
                    </a>
                </li>
            @endif

            @php($perm =  $userPermissions->where('name','=','special-category')->first() )
            @if( auth()->user()->hasRole('administrator') ||
                  ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
            )


                <li class="nav-item ">
                    <a href="{{route('manager.services.category.index')}}" class="nav-link {{ request()->is('panel/services/category')  ? 'active' : ''}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            კატეგორიები
                        </p>
                    </a>
                </li>
                @if( auth()->user()->hasRole('administrator'))
                <li class="nav-item ">
                    <a href="{{route('manager.services.category.statistics')}}" class="nav-link {{ request()->is('manager.services.category.statistics**')  ? 'active' : ''}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            სტატისტიკა
                        </p>
                    </a>
                </li>
                @endif
            @endif

            @php($perm =  $userPermissions->where('name','=','user')->first() )
            @if( auth()->user()->hasRole('administrator') ||
                    ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
            )

                <li class="nav-item">
                    <a href="{{route('manager.users.index')}}" class="nav-link {{ request()->routeIs('manager.users.index*') || request()->routeIs('manager.users.create*') || request()->routeIs('manager.users.show*') ? 'active' : ''  }}">
                        <i class="far fa-circle nav-icon"> </i>
                        <p>
                            {{__('panel.users')}}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('manager.roles.index')}}" class="nav-link {{ request()->routeIs('manager.roles*') ? 'active' : ''  }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{__('panel.roles')}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('manager.users.contacts')}}" class="nav-link {{ request()->routeIs('manager.users.contacts*') ? 'active' : ''  }}">
                        <i class="far fa-circle nav-icon"> </i>
                        <p>
                            კონტაქტები
                        </p>
                    </a>
                </li>

            @endif

            @php($perm =  $userPermissions->where('name','=','blog')->first() )
            @if( auth()->user()->hasRole('administrator') ||
                  ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
            )

                <li class="nav-item ">
                    <a href="{{route('manager.blog.post.index')}}" class="nav-link {{ request()->routeIs('manager.blog.post**')  ? 'active' : ''}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                           ბლოგი
                        </p>
                    </a>
                </li>

            @endif

            @php($perm = $userPermissions->where('name', '=', 'service')->first())
            @if(auth()->user()->hasRole('administrator') ||
                ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
            )
                <li class="nav-item  {{ request()->routeIs('manager.services.review*') ? 'menu-is-opening menu-open active' : ''  }}">
                    <a href="{{route('manager.services.review.index')}}" class="nav-link {{ request()->routeIs('manager.services.review**')  ? 'active' : ''}}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            შეფასებები
                        </p>
                    </a>
                </li>
            @endif

            <li class="nav-item {{
                request()->is('panel/contact-requests**') ||
                 request()->is('panel/payment-requests**') ||
                 request()->is('panel/call-requests**') ||
                 request()->is('panel/orders**') ||
                 request()->is('panel/configuration/**') ||
                 request()->routeIs('manager.payable-packet*') ? 'menu-is-opening menu-open' : ''
                }}">
                <a href="" class="nav-link ">
                    <i class="nav-icon fas fa-bars"></i>
                    <p>
                        სხვადასხვა
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @php($perm =  $userPermissions->where('name','=','contact-requests')->first() )
                    @if( auth()->user()->hasRole('administrator') ||
                            ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                    )
                        <li class="nav-item  {{ request()->routeIs('manager.contact-requests*') ? 'menu-is-opening menu-open active' : ''  }}">
                            <a href="{{route('manager.contact-requests.list')}}" class="nav-link  {{ request()->routeIs('manager.contact-requests*') ? 'active' : ''  }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>
                                    კონტაქტი
                                    <span class="badge badge-info right">
                        {{\App\Models\ContactRequests::where('seen',false)->count()}}
                        </span>
                                </p>
                            </a>
                        </li>
                    @endif


                    @if( auth()->user()->hasRole('administrator') )
                        <li class="nav-item  {{ request()->routeIs('manager.payable-packet*') ? 'menu-is-opening menu-open active' : ''  }}">
                            <a href="{{route('manager.payable-packet.list')}}" class="nav-link  {{ request()->routeIs('manager.payable-packet*') ? 'active' : ''  }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>
                                    VIP პაკეტები
                                </p>
                            </a>
                        </li>
                    @endif

                    @php($perm =  $userPermissions->where('name','=','payment-requests')->first() )
                    @if( auth()->user()->hasRole('administrator') ||
                          ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                    )
                        <li class="nav-item  {{ request()->routeIs('manager.payment-requests*') ? 'menu-is-opening menu-open active' : ''  }}">
                            <a href="{{route('manager.payment-requests.list')}}" class="nav-link  {{ request()->routeIs('manager.payment-requests*') ? 'active' : ''  }}">
                                <i class="nav-icon fas fa-money-bill"></i>
                                <p>
                                    თანხის გატანა
                                    <span class="badge badge-info right">
                        {{\App\Models\UserWithdrawalRequests::where('status','=','in_progress')->count()}}
                        </span>
                                </p>
                            </a>
                        </li>
                    @endif

                    @php($perm =  $userPermissions->where('name','=','call-requests')->first() )
                    @if( auth()->user()->hasRole('administrator') ||
                          ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                    )
                        <li class="nav-item  {{ request()->routeIs('manager.call-requests*') ? 'menu-is-opening menu-open active' : ''  }}">
                            <a href="{{route('manager.call-requests.list')}}" class="nav-link  {{ request()->routeIs('manager.call-requests*') ? 'active' : ''  }}">
                                <i class="nav-icon fas fa-phone"></i>
                                <p>
                                    ზარები
                                    <span class="badge badge-info right">
                        {{\App\Models\CallRequests::where('is_called',false)->count()}}
                        </span>
                                </p>
                            </a>
                        </li>
                    @endif

                    @php($perm =  $userPermissions->where('name','=','orders')->first() )
                    @if( auth()->user()->hasRole('administrator') ||
                          ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                    )
                        <li class="nav-item  {{ request()->routeIs('manager.orders*') ? 'menu-is-opening menu-open active' : ''  }}">
                            <a href="{{route('manager.orders.list')}}" class="nav-link {{ request()->routeIs('manager.orders**')  ? 'active' : ''}}">
                                <i class="nav-icon fas fas fa-shopping-basket"></i>
                                <p>
                                    შეკვეთები
                                </p>
                            </a>
                        </li>
                    @endif

                    @php($perm =  $userPermissions->where('name','=','configuration')->first() )
                    @if( auth()->user()->hasRole('administrator') ||
                          ( $perm  != null && ($perm->pivot->can_add || $perm->pivot->can_edit  || $perm->pivot->can_delete) )
                    )
                        <li class="nav-item {{ request()->routeIs('manager.configuration*') ? 'menu-is-opening menu-open active' : ''  }}">

                            <a href="#" class="nav-link {{ request()->routeIs('manager.configuration*') ? 'active' : ''  }}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    {{__('panel.configuration')}}
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{route('manager.configuration.locales.index')}}" class="nav-link {{ request()->routeIs('manager.configuration.locales*') ? 'active' : ''  }}">
                                        <i class="far fa-circle nav-icon"> </i>
                                        <p>
                                            {{__('panel.locales')}}
                                        </p>
                                    </a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{route('manager.configuration.smsoffice.basic')}}" class="nav-link {{ request()->routeIs('manager.configuration.smsoffice.basic*') ? 'active' : ''  }}">
                                        <i class="far fa-circle nav-icon"> </i>
                                        <p>
                                            {{__('panel.smsoffice-menu')}}
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item  {{ request()->routeIs('manager.faq*') ? 'menu-is-opening menu-open active' : ''  }}">
                                    <a href="{{route('manager.faq.list')}}" class="nav-link  {{ request()->routeIs('manager.faq*') ? 'active' : ''  }}">
                                        <i class="far fa-circle nav-icon"> </i>
                                        <p>
                                            როგორ მუშაობს
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item  {{ request()->routeIs('manager.slider*') ? 'menu-is-opening menu-open active' : ''  }}">
                                    <a href="{{route('manager.slider.list')}}" class="nav-link  {{ request()->routeIs('manager.slider*') ? 'active' : ''  }}">
                                        <i class="nav-icon far fa-image"></i>
                                        <p>
                                            სლაიდერი
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item  {{ request()->routeIs('manager.ads*') ? 'menu-is-opening menu-open active' : ''  }}">
                                    <a href="{{route('manager.ads.list')}}" class="nav-link  {{ request()->routeIs('manager.ads*') ? 'active' : ''  }}">
                                        <i class="nav-icon fas fa-image"></i>
                                        <p>
                                            ADS
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item  {{ request()->routeIs('manager.city*') ? 'menu-is-opening menu-open active' : ''  }}">
                                    <a href="{{route('manager.city.list')}}" class="nav-link  {{ request()->routeIs('manager.city*') ? 'active' : ''  }}">
                                        <i class="nav-icon fas fa-city"></i>
                                        <p>
                                            ქალაქები
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif

                </ul>
            </li>



        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
