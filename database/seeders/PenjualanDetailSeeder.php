<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) { // Loop untuk setiap transaksi penjualan (10 transaksi)
            for ($j = 1; $j <= 3; $j++) { // 3 barang per transaksi
                $data[] = [
                    'penjualan_id' => $i, // Mengacu pada penjualan_id di t_penjualan
                    'barang_id' => rand(1, 10), // Mengambil barang_id acak dari m_barang (barang 1-10)
                    'harga' => rand(10000, 100000), // Harga acak untuk setiap barang
                    'jumlah' => rand(1, 5), // Jumlah barang yang dibeli secara acak
                ];
            }
        }
        DB::table('t_penjualan_detail')->insert($data); // Memasukkan data ke tabel t_penjualan_detail
    }
}

