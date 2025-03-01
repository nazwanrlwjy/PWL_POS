<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Barang dari Supplier 1: PT. Elektronika Wijaya
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'ELK001', 'barang_nama' => 'Laptop Dell Inspiron 15', 'harga_beli' => 6500000, 'harga_jual' => 8000000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'ELK002', 'barang_nama' => 'Smartphone iPhone 13', 'harga_beli' => 12000000, 'harga_jual' => 15000000],
            ['barang_id' => 3, 'kategori_id' => 1, 'barang_kode' => 'ELK003', 'barang_nama' => 'Headphone Bose QuietComfort 35', 'harga_beli' => 4000000, 'harga_jual' => 5500000],
            ['barang_id' => 4, 'kategori_id' => 1, 'barang_kode' => 'ELK004', 'barang_nama' => 'Kamera DSLR Nikon D3500', 'harga_beli' => 7000000, 'harga_jual' => 8500000],

            // Barang dari Supplier 2: CV. Wijaya Fashion
            ['barang_id' => 5, 'kategori_id' => 2, 'barang_kode' => 'PAK001', 'barang_nama' => 'Kemeja Pria Slim Fit', 'harga_beli' => 180000, 'harga_jual' => 250000],
            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'PAK002', 'barang_nama' => 'Jaket Kulit Asli Wanita', 'harga_beli' => 500000, 'harga_jual' => 750000],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'PAK003', 'barang_nama' => 'Kaos Polo Ralph Lauren', 'harga_beli' => 250000, 'harga_jual' => 350000],
            ['barang_id' => 8, 'kategori_id' => 2, 'barang_kode' => 'PAK004', 'barang_nama' => 'Celana Jeans Wrangler', 'harga_beli' => 300000, 'harga_jual' => 400000],

            // Barang dari Supplier 3: UD. Sembako Makmur Jaya
            ['barang_id' => 9, 'kategori_id' => 3, 'barang_kode' => 'MMN001', 'barang_nama' => 'Beras Organik 5kg', 'harga_beli' => 60000, 'harga_jual' => 80000],
            ['barang_id' => 10, 'kategori_id' => 3, 'barang_kode' => 'MMN002', 'barang_nama' => 'Minyak Goreng Kelapa 1L', 'harga_beli' => 22000, 'harga_jual' => 28000],
        ];
        DB::table('m_barang')->insert($data);
    }
    }