<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Peralatan elektronik'],
            ['nama_kategori' => 'Pakaian', 'deskripsi' => 'Berbagai jenis pakaian'],
            ['nama_kategori' => 'Makanan', 'deskripsi' => 'Makanan ringan dan berat'],
            ['nama_kategori' => 'Minuman', 'deskripsi' => 'Minuman segar dan kemasan'],
            ['nama_kategori' => 'Alat Tulis', 'deskripsi' => 'Keperluan kantor dan sekolah'],
        ];

        DB::table("m_kategori")->insert($data);
    }
}
