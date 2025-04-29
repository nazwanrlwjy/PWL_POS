<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['supplier_kode' => 'SUP001', 'supplier_nama' => 'CV Wijaya Kusuma', 'supplier_alamat' => 'Jl. Abimanyi No. 1', 'supplier_telp' => '081287537890'],
            ['supplier_kode' => 'SUP002', 'supplier_nama' => 'PT Cahaya Berjaya', 'supplier_alamat' => 'Jl. Bunga Mawar No. 9', 'supplier_telp' => '081298987432'],
            ['supplier_kode' => 'SUP003', 'supplier_nama' => 'UD Berkah Berasama', 'supplier_alamat' => 'Jl. Jatimulyo No. 10', 'supplier_telp' => '081212387648'],
            ['supplier_kode' => 'SUP004', 'supplier_nama' => 'Toko Sidu', 'supplier_alamat' => 'Jl. Ahmad Hidayat No. 18', 'supplier_telp' => '082398658641'],
            ['supplier_kode' => 'SUP005', 'supplier_nama' => 'Distributor Abimanyu', 'supplier_alamat' => 'Jl. Kaca Gatot No. 5', 'supplier_telp' => '087536789012'],
        ];

        DB::table('m_supplier')->insert($suppliers);
    }
}