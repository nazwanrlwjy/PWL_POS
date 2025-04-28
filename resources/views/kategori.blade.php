<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori Barang</title>
</head>
<body>
    <h1>Data Kategori Barang</h1>
    <table border="1" cellPadding="2" cellSpacing="0">
        <tr>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->kategori_kode }}</td>
            <td>{{ $d->nama_kategori }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>