<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'm_user';  // Nama tabel yang benar
    protected $primaryKey = 'user_id'; // Primary key yang sesuai
    public $timestamps = false; // Karena `created_at` dan `updated_at` NULL

    protected $fillable = [
        'name',
        'email',
        'password',
    ];    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
    
}
