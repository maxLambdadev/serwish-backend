


@foreach($fields as $k => $textInput)
    <div class="form-group row">
        {!! $textInput->transform([]) !!}
    </div>
@endforeach
