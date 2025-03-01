<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'stok_id' => $i,
                'barang_id' => $i, // barang_id mengacu pada barang yang ada di tabel m_barang
                'user_id' => rand(1, 3),// user_id mengacu pada user yang ada di tabel m_user
                'stok_tanggal' => Carbon::now()->subDays($i), // tanggal stok yang berbeda setiap hari
                'stok_jumlah' => rand(10, 100), // jumlah stok acak antara 10 hingga 100
            ];
        }
        DB::table('t_stok')->insert($data); // Memasukkan data ke tabel t_stok
    }
}
