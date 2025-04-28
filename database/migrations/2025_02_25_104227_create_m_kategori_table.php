<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_kategori', function (Blueprint $table) {
            $table->id(); // Primary Key (id auto increment)
            $table->string('nama_kategori', 100)->unique(); // Nama kategori unik
            $table->text('deskripsi')->nullable(); // Deskripsi kategori, bisa kosong
            $table->timestamps(); // Kolom created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_kategori');
    }
};