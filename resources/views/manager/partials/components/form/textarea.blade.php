@if(isset($title) && $title !== null )
        <label for="{{$name}}">{{$title}}</label>
    @endif
<div class="{{$language ? 'row' : ''}}  form-group">

    <div class="{{$language ? 'col-md-11' : 'col-md-12'}}">
        <textarea id="{{$name}}"
                  @if($language)
                    name="{{$defaultLocale->iso_code}}[{{$name}}]"
                  @else
                    name="{{$name}}"
                  @endif
            class="summernote-active summernote input-picker"
        >@if($entity != null){!! $entity->{$name} !!}@endif</textarea>

        @if($language)
            @foreach($locales as $l)
                @if($l['iso_code'] != $defaultLocale->iso_code)
                    <textarea id="{{$name}}"
                              @if($language)
                              name="{{$l['iso_code']}}[{{$name}}]"
                              @else
                              name="{{$name}}"
                              @endif
                              class="summernote input-picker" style="display: none"
                    >@if($entity != null && $entityKey != null){!! $entity->translate($l['iso_code']) == null ? '' : $entity->translate($l['iso_code'])->{$name} !!}@endif</textarea>
                @endif
            @endforeach
        @endif


    </div>

    @if($language)
        <div class="col-md-1">
                @include('manager.partials.components.form.languageDropdown',[
                     'input' => $name
                ])
        </div>
    @endif

</div>



