<div class="form-group">
    <label for="languageSwitcher">&nbsp;</label>
    <select name="languageSwitcher"  class="form-control languageSwitcher">
        <option value="{{$defaultLocale->iso_code}}" selected>{{$defaultLocale->iso_code}}</option>
        @foreach($locales as $l)
            @if($l['iso_code'] != $defaultLocale->iso_code)
                <option value="{{$l['iso_code']}}">{{$l['iso_code']}}</option>
            @endif
        @endforeach
    </select>
</div>

