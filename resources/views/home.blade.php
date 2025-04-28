<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Point of Sales (POS)</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Selamat Datang di POS</h1>
        <p class="text-center">Aplikasi untuk mempermudah manajemen penjualan Anda.</p>

        <h2 class="mt-4">Kategori Produk</h2>
        <div class="list-group">
            <a href="{{ url('/category/food-beverage') }}" class="list-group-item list-group-item-action">🍔 Food & Beverage</a>
            <a href="{{ url('/category/beauty-health') }}" class="list-group-item list-group-item-action">💄 Beauty & Health</a>
            <a href="{{ url('/category/home-care') }}" class="list-group-item list-group-item-action">🏠 Home Care</a>
            <a href="{{ url('/category/baby-kid') }}" class="list-group-item list-group-item-action">👶 Baby & Kid</a>
        </div>
    </div>

    <footer class="text-center mt-5 p-3 bg-light">
        <p>&copy; {{ date('Y') }} POS - All Rights Reserved.</p>
    </footer>
</body>
</html>
