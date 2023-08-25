<div class="form-group">
        <input type="checkbox"
               id="{{$name}}" name="{{$name}}"
               data-bootstrap-switch
               data-off-color="danger"
               data-on-color="success"
               @if($entity != null && $entityKey != null && $entity->{$entityKey}) checked  @endif
        >

    <label for="{{$name}}"> {{ __('panel.'.$name)}} </label>
</div>



