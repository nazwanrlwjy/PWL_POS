<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelModel extends Model
{
    protected $table = 'm_level'; // Nama tabel di database
    protected $primaryKey = 'level_id'; // Primary key yang benar
    public $timestamps = false; // Matikan timestamps jika tidak digunakan

    protected $fillable = ['level_name', 'level_kode']; // Tambahkan level_kode jika diperlukan

    /**
     * Relasi ke UserModel (satu level bisa memiliki banyak user).
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'level_id', 'level_id'); 
        // level_id ada di tabel users sebagai foreign key
    }
}