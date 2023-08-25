


@foreach($fields as $textInput)
    <div class="form-group row">
        {!! $textInput->transform([]) !!}
    </div>
@endforeach
