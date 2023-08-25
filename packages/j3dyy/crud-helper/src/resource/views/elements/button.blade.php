@if($button->link != null)
    <a href="{{\J3dyy\CrudHelper\Helper::parseLink($button->link,$entity)}}">
@endif
<button
    @if($button->type != null) type="{{$button->type}}" @endif
    @if($button->classes != null) class="{{$button->classes}}" @endif
    @if($button->id != null) id="{{$button->id}}" @endif
    @foreach($button->dataAttrs as $key => $attr)
        data-{{$key}}="{{\J3dyy\CrudHelper\Helper::parseLink($attr,$entity)}}"
   @endforeach
>
    {!! $button->content !!}
</button>
@if($button->link != null)
</a>
@endif

