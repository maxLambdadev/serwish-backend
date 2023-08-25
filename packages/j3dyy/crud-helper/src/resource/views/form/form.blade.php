@if(isset($form))
<form class="form-horizontal {!! $form->classes !== null ? $form->classes : '' !!}" {!! $form->id !== null ? 'id="'.$form->id.'"' : '' !!} action="{{$form->action}}" method="{{$form->method}}">
    <div class="card-body">
        @foreach($form->elements as $type => $fields)
            @include("crudHelper::form.fields",[
                    'fields' => $fields,
                    'type'  => $type
            ])
        @endforeach
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        @foreach($form->actionButtons as $button)
            {!! $button->transform([]) !!}
        @endforeach
    </div>
    <!-- /.card-footer -->
</form>
@endif
