<td
 data-column="{{$column->key}}"
 {!! $column->classes !== null ? 'class="'.$column->classes.'"' : '' !!}
>

    @if($column->type == J3dyy\CrudHelper\Elements\ElementTypes::COLUMN)
        @if($column->key == 'isactive' || $column->key == 'isActive')
            @if($entity[$column->key] == 1) {{__('base.active')}} @else {{__('base.inactive')}} @endif
        @else
            @if(isset($entity[$column->key]) && !is_array($entity[$column->key]))
                {{ $entity[$column->key] }}
            @else
{{--                when not set trying to find relational connection--}}
{{--                ex if client wants display relational field he must enter :--}}
{{--                relation.field,  more ex: relationalEntity.id--}}
                @php($exploded = explode('.',$column->key))
                @if(count($exploded) == 2 && isset($entity[$exploded[0]]))
                    @php($relationalEntity = $entity[$exploded[0]])
                    @if(isset($relationalEntity[$exploded[1]]))
                        {{ $relationalEntity[$exploded[1]] }}
                    @endif
                    @else
{{--                    {!! dd($exploded) !!}--}}
                @endif
            @endif
        @endif
    @elseif($column->type == J3dyy\CrudHelper\Elements\ElementTypes::BUTTON || $column->type == J3dyy\CrudHelper\Elements\ElementTypes::HTML)
        {{ $column->content->transform($entity)  }}
    @endif
</td>
