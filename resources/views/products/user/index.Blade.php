<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>

    <h1>Data User</h1>

    <table border="1" cellPadding="5" cellSpacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>ID Level Pengguna</th>
        </tr>
        @foreach ($users as $d)
        <tr>
            <td>{{ $d->user_id }}</td>  <!-- Sesuai dengan kolom di database -->
            <td>{{ $d->username }}</td>
            <td>{{ $d->name }}</td>  <!-- Ubah dari nama ke name -->
            <td>{{ $d->level_id }}</td>
        </tr>
        @endforeach
    </table>

</body>
</html>
