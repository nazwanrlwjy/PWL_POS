<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->foreignId('kategori_id')->constrained('m_kategori')->onDelete('cascade');
            $table->string('barang_kode', 10)->unique();
            $table->string('barang_nama', 100);
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('m_barang');
    }
};