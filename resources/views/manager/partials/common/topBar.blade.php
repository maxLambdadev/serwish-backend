<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="toggle-sidebar-nav"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->



        <li class="nav-item dropdown">

        </li>

        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-phone"></i>
                <span class="badge badge-warning navbar-badge">{{\App\Models\CallRequests::where('is_called',false)->count()}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">ზარის მოთხოვნები</span>
                @foreach($callReqs as $callReq)
                    <a href="{{route('manager.call-requests.list', ['highlight'=>$callReq->id])}}" class="dropdown-item">
                        {{$callReq->phone_number}}  {!! $callReq->category != null ? $callReq->category->title : '' !!}
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{ \App\Models\Services::where('review_status','=','started')->count()}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">ახალი სერვისები</span>
                @foreach($newServices as $newService)
                    <a href="{{ route('manager.services.service.show',['id'=>$newService->id])  }}" class="dropdown-item">
                        {{\Illuminate\Support\Str::limit($newService->title,15)}} {{\Illuminate\Support\Str::limit($newService->specialist->name,15)}}
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach
            </div>
        </li>

    </ul>
</nav>
