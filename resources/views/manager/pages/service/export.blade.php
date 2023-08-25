

<table class="table table-hover text-nowrap">
    <thead>
    <tr>

        <th>ID</th>
        <th>სერვისი</th>
        <th>ლოკაცია</th>
        <th>შემსრულებელი</th>
        <th>სპეციალობა</th>
        <th>ფასი</th>
        <th>შექმნის თარიღი</th>
        <th>სტატუსი</th>
    </tr>

    </thead>
    <tbody>
    @foreach($list as $l)
        <tr >
            <td>{{$l->id}}</td>
            <td>{{$l->title}}</td>
            <td>{{$l->location}}</td>
            <td>
                {{$l->specialist->name}}
            </td>
            <td>
                    @foreach($l->categories as $category)
                       {{$category->title == null ? 'სახელი არ აქვს' : $category->title}} ,
                    @endforeach
            </td>
            <td>{{$l->price}}</td>
            <td>{{$l->created_at}}</td>
            <td>{{$l->is_active ? 'ჩართული':'გამორთული'}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
