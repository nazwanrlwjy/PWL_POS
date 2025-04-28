<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'barang_kode' => 'B001', 'barang_nama' => 'Laptop Asus', 'harga_beli' => 6000000, 'harga_jual' => 7000000],
            ['kategori_id' => 1, 'barang_kode' => 'B002', 'barang_nama' => 'TV Samsung', 'harga_beli' => 3500000, 'harga_jual' => 4000000],
            ['kategori_id' => 2, 'barang_kode' => 'B003', 'barang_nama' => 'Kaos Polos', 'harga_beli' => 40000, 'harga_jual' => 50000],
            ['kategori_id' => 2, 'barang_kode' => 'B004', 'barang_nama' => 'Jaket Hoodie', 'harga_beli' => 120000, 'harga_jual' => 150000],
            ['kategori_id' => 3, 'barang_kode' => 'B005', 'barang_nama' => 'Roti Tawar', 'harga_beli' => 12000, 'harga_jual' => 15000],
            ['kategori_id' => 3, 'barang_kode' => 'B006', 'barang_nama' => 'Coklat Batang', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['kategori_id' => 4, 'barang_kode' => 'B007', 'barang_nama' => 'Air Mineral', 'harga_beli' => 4000, 'harga_jual' => 5000],
            ['kategori_id' => 4, 'barang_kode' => 'B008', 'barang_nama' => 'Kopi Botol', 'harga_beli' => 10000, 'harga_jual' => 12000],
            ['kategori_id' => 5, 'barang_kode' => 'B009', 'barang_nama' => 'Pulpen', 'harga_beli' => 2500, 'harga_jual' => 3000],
            ['kategori_id' => 5, 'barang_kode' => 'B010', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 8000, 'harga_jual' => 10000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
