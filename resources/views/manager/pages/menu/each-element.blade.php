



@if (!$menu->childrens->isEmpty())
    <li class="dd-item dd3-item" data-id="15">
        <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">Item {{$menu->id}}</div>
        <ol class="dd-list">
            @foreach($menu->childrens as $children)
                @include('manager.pages.menu.each-element',['menu'=>$children])
            @endforeach
        </ol>
    </li>
@else
    <li class="dd-item dd3-item" data-id="13">
        <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">Item {{$menu->id}}</div>
    </li>
@endif



