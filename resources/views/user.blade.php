<table>
    <thead>
    <tr>
        <th> Name1</th>
        <th> Email1</th>
    </tr>
    <tr>
        <th> Name2</th>
        <th> Email2</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
