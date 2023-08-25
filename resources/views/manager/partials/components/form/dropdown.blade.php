{{--todo refactor translatable dropdown--}}
<div class="form-group">
    <label for="{{$name}}">{{$title}}</label>
    <select name="{{$name}}[]" id="role-{{$name}}" class="form-control {{$name}}-select2bs4" multiple="multiple" data-placeholder="{{$defaultValue}}">
        @foreach($list as $l)
            <option value="{{$l[$value]}}"
            @if($entity != null)
                @php($rt = $entity->{$related}->where("$value", '=',$l[$value])->first())
                @if ($rt != null)
                    selected="selected"
                @endif
            @endif
            >

                {{isset($l['translated'][0]) ? $l['translated'][0][$displayName] : __('panel.cannotDisplayName')}}
            </option>
        @endforeach
    </select>
</div>

@push('script')
<script>
    $('.{{$name}}-select2bs4').select2({
        theme: 'bootstrap4',
        tags: {{$tags == 1 ? 'true' : 'false'}}
    })
</script>
@endpush
