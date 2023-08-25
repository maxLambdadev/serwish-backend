


@foreach($button->buttonGroups as $b)
    <div class="btn-group">
    @if($b->link != null)
        <a href="{{\J3dyy\CrudHelper\Helper::parseLink($b->link,$entity)}}">
            @endif
            <button
                @if($b->classes != null) class="{{$b->classes}}" @endif
            @if($b->id != null) id="{{$b->id}}" @endif
                @foreach($b->dataAttrs as $key => $attr)
                data-{{$key}}="{{\J3dyy\CrudHelper\Helper::parseLink($attr,$entity)}}"
                @endforeach
            >
            {!! $b->content !!}
            </button>
            @if($b->link != null)
        </a>
    @endif
    </div>

@endforeach
{{--@if($button->link != null)--}}
{{--    <a href="{{\J3dyy\CrudHelper\Helper::parseLink($button->link,$entity)}}">--}}
{{--@endif--}}
{{--<button--}}
{{--    @if($button->classes != null) class="{{$button->classes}}" @endif--}}
{{--    @if($button->id != null) id="{{$button->id}}" @endif--}}
{{--    @foreach($button->dataAttrs as $key => $attr)--}}
{{--        data-{{$key}}="{{\J3dyy\CrudHelper\Helper::parseLink($attr,$entity)}}"--}}
{{--   @endforeach--}}
{{-->--}}
{{--    {!! $button->content !!}--}}
{{--</button>--}}
{{--@if($button->link != null)--}}
{{--</a>--}}
{{--@endif--}}

