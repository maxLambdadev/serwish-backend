
<div class="btn-group">
    <button type="button" class="btn btn-info btn-flat">Action</button>
    <button type="button" class="btn btn-info btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu" role="menu">
        <a class="dropdown-item" href="{{$editRoute}}">Edit</a>
        <a class="dropdown-item deleteTrigger"  href="{{$deleteRoute}}">Delete</a>
    </div>
</div>
