<table >
    <thead>
    <tr>

        <th>ID</th>
        <th>სათაური</th>
        <th>სერვისი</th>
        <th>ავტორი</th>
        <th>სტატუსი</th>
        <th>შექმნის თარიღი</th>
    </tr>

    </thead>
    <tbody>
    @foreach($list as $l)
        <tr >

            <td>{{$l->id}}</td>
            <td>{{$l->title}}</td>
            <td>
                {{$l->services != null  ? $l->services->count(): 0 }} - სერვისი
            </td>
            <td>{{$l->author->email}}</td>
            <td>{{$l->isActive ? 'ჩართული' : 'გამორთული'}}</td>
            <td>{{$l->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
