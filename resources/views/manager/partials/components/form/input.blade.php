<div class="{{$language ? 'row' : ''}} {{$group == null ? 'form-group' : 'input-group mb-3'}}">

    <div class="{{$language ? 'col-md-11' : 'col-md-12'}}">
        @if($group !== null )
            <div class="input-group-prepend">
                <span class="input-group-text">{{$group}}</span>

        @else
            <label for="{{$name}}">{{$title}}</label>
        @endif

                <input type="{{$type}}"
               @if($language)
                    name="{{$defaultLocale->iso_code}}[{{$name}}]"
               @else
                    name="{{$name}}"
               @endif
               class="form-control input-picker @if($multiLangClass) {{$extraClasses}}-{{$defaultLocale->iso_code}} @else {{$extraClasses}} @endif"
               data-locale="{{$defaultLocale->iso_code}}"
               id="{{$name}}"
               placeholder="{{$placeholder}}"
               @if($entity != null) value="{{ $entity->{$name} }}" @endif
        >
       @if($language)
           @foreach($locales as $l)
               @if($l['iso_code'] != $defaultLocale->iso_code)
                        <input type="text" class="form-control input-picker  @if($multiLangClass) {{$extraClasses}}-{{$l['iso_code']}} @else {{$extraClasses}} @endif "
                               data-locale="{{$l['iso_code']}}"
                               name="{{$l['iso_code']}}[{{$name}}]"
                               @if($entity != null && $entityKey != null) value="{{ $entity->translate($l['iso_code']) == null ? '' : $entity->translate($l['iso_code'])->{$name} }}" @endif
                               style="display: none" />
               @endif
           @endforeach
       @endif
            @if($group !== null )  </div> @endif
    </div>
    @if($language)
        <div class="col-md-1">
            @include('manager.partials.components.form.languageDropdown',[
                'input' => $name
           ])
        </div>
    @endif

</div>


