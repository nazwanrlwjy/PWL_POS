<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'penjualan_id' => $i, // ID transaksi penjualan
                'user_id' => rand(1, 3), // Menggunakan user_id yang ada di tabel m_user (1, 2, atau 3)
                'pembeli' => 'Pembeli ' . $i, // Nama pembeli
                'penjualan_kode' => 'PJ' . str_pad($i, 3, '0', STR_PAD_LEFT), // Kode transaksi unik (PJ001, PJ002, ...)
                'penjualan_tanggal' => Carbon::now()->subDays($i), // Tanggal transaksi dengan interval hari yang berbeda
            ];
        }
        DB::table('t_penjualan')->insert($data); // Memasukkan data ke tabel t_penjualan
    }
}
