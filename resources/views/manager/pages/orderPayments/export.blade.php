<table class="table table-hover text-nowrap">
    <thead>
    <tr>
        <th>სერვისი</th>
        <th>სტატუსი</th>
        <th>შემკვეთი</th>
        <th>შემსრულებელი</th>
        <th>მოქმედება</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $l)
        <tr id="tr-{{$l->id}}"  @if(request()->get('highlight') && request()->get('highlight') == $l->id) class="info-bg" @endif >
            <td>{{ $l->service->title}}</td>
            <td>{{$l->room_state}}</td>
            <td>
              {{$l->customer->name}} - {{$l->customer->phone_number}}
            </td>
            <td>
               {{$l->specialist->name}} - {{$l->specialist->phone_number}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
