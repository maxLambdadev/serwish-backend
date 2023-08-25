@if(isset($permission))
<?php
        $role = Auth::user()->roles;
        $pName =  \Spatie\Permission\Models\Permission::where('name','=',$permission)->first();
        if ($pName !== null){
            $perm = \Illuminate\Support\Facades\DB::table('role_has_permissions')->select('permission_id','can_edit','can_delete','can_add')
                ->where('permission_id','=',$pName->id)
                ->where('role_id','=',$role->first()->id)
                ->first();

        }
?>
@endif


<div class="btn-group">
{{--    <button type="button" class="btn btn-info btn-flat">მოქმედება</button>--}}
    <button type="button" class="btn btn-info btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu" role="menu">

            @if(isset($extra) && is_array($extra))
                @foreach($extra as $e)
                    <a href="{{$e['route']}}" class="dropdown-item">{{$e['name']}}</a>
                @endforeach
                <div class="dropdown-divider"></div>
            @endif

            @isset($editRoute)
                @if( auth()->user()->hasRole('administrator') || (isset($perm) && $perm->can_edit === true) )
                        <a class="dropdown-item" href="{{$editRoute}}">{!! isset($customEditName) ? $customEditName : 'რედაქტირება' !!}</a>
               @endif
            @endisset

            @isset($deleteRoute)
                @if( auth()->user()->hasRole('administrator') ||  (isset($perm) && $perm->can_delete === true) )
                    <a class="dropdown-item deleteTrigger"  href="{{$deleteRoute}}">წაშლა</a>
                @endif
            @endisset
    </div>
</div>
