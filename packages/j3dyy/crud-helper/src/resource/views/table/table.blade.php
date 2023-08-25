@if(isset($table))

<table
    {!! $table->classes !== null ? 'class="'.$table->classes.'"' : '' !!}
    {!! $table->id !== null ? 'id="'.$table->id.'"' : '' !!}
>
  <thead>
  @foreach($table->columns as $c)
      @if(is_string($c)) @continue @endif
      <th data-column="{{$c->key}}"
          data-type="{{$c->type}}"
          @if($c->classes != null)
          class="{{ $c->classes }}"
          @endif
      >
          {{$c->label}}
      </th>

  @endforeach
  </thead>

   <tbody>
   @foreach($table->items as $i)
       <tr data-id="{{ $i['id'] }}">
           @foreach($table->columns as $column)
               {!! $column->transform($i) !!}
           @endforeach
       </tr>
   @endforeach
   </tbody>
</table>
@endif
