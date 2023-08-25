

<table class="table table-hover text-nowrap">
    <thead>
    <tr>

        <th>ID</th>
        <th>სახელი</th>
        <th>ელ.ფოსტა</th>
        <th>ტელეფონი</th>
        <th>დაბადების თარიღი</th>
        <th>ID ნომერი</th>
        <th>ფიზიკური პირი</th>
        <th>მომხმარებლის ტიპი</th>
        <th>შექმნის თარიღი</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $l)
        <tr >
            <td>{{$l->id}}</td>
            <td>{{$l->name}}</td>
            <td>{{$l->email}}</td>
            <td>{{$l->phone_number}}</td>
            <td>{{$l->date_of_birth}}</td>
            <td>{{$l->id_number}}</td>
            <td>{{$l->personal == 'personal' ? 'ფიზიკური პირი':'იურიდიული პირი'}}</td>
            <td>{{$l->client_type == 'employee' ? 'მომხმარებელი':'შემსრულებელი'}}</td>
            <td>{{$l->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
