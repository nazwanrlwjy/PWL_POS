<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Data yang bisa ditampilkan di halaman home
        $categories = [
            ['name' => 'Food & Beverage', 'url' => '/category/food-beverage'],
            ['name' => 'Beauty & Health', 'url' => '/category/beauty-health'],
            ['name' => 'Home Care', 'url' => '/category/home-care'],
            ['name' => 'Baby & Kid', 'url' => '/category/baby-kid'],
        ];

        $title = "Selamat Datang di Point of Sales (POS)";
        $description = "Aplikasi POS yang membantu Anda dalam manajemen penjualan dengan mudah dan efisien.";

        return view('home', compact('title', 'description', 'categories'));
    }
}
