{!! dd($entity) !!}
<div class="form-group">
    <label for="{{$name}}">{{$title}}</label>
    <select name="{{$name}}" id="role" class="form-control">
        <option value="">{{$defaultValue}}</option>
        @foreach($list as $l)
            <option value="{{$l[$value]}}"
            @if($entity !== null)

                selected="selected"
            @endif
            >{{$l[$displayName]}}</option>
        @endforeach
    </select>
</div>
