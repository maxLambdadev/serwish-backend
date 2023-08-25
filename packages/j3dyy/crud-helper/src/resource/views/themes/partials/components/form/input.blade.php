<div class="{{$group == null ? 'form-group' : 'input-group mb-3'}}">
    @if($group !== null )
        <div class="input-group-prepend">
            <span class="input-group-text">{{$group}}</span>
        </div>
    @else
        <label for="{{$name}}">{{$title}}</label>
    @endif
        <input type="{{$type}}" name="{{$name}}" class="form-control" id="{{$name}}"
               placeholder="{{$placeholder}}"
               value="{{$entity}}"
        >
</div>


