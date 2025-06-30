<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login'); // menampilkan halaman login
Route::post('login', [AuthController::class, 'postlogin']); // proses login
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth'); // proses logout
Route::get('/register', [AuthController::class, 'register'])->name('register.form');
Route::post('/register', [AuthController::class, 'store_user'])->name('register.store');

Route::middleware('auth')->group(function () {

    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])->prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);       // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']);    // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
        Route::post('/', [UserController::class, 'store']);      // menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);      // menyimpan data user baru ajax
        Route::get('/{id}', [UserController::class, 'show']);    // menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
        Route::put('/{id}', [UserController::class, 'update']);  // menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);  // menyimpan perubahan data user ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // menampilkan halaman form confirm delete user ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);  // untuk hapus data user ajax
        Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
        Route::get('import', [UserController::class, 'import']);
        Route::post('import_ajax', [UserController::class, 'import_ajax']);
        Route::get('export_excel', [UserController::class, 'export_excel']);
        Route::get('export_pdf', [UserController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {
        Route::get('/', [LevelController::class, 'index']);       // menampilkan halaman awal level
        Route::post('/list', [LevelController::class, 'list']);    // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/create', [LevelController::class, 'create']); // menampilkan halaman form tambah level
        Route::post('/', [LevelController::class, 'store']);      // menyimpan data level baru
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // menampilkan halaman form tambah level
        Route::post('/ajax', [LevelController::class, 'store_ajax']);      // menyimpan data level baru
        Route::get('/{id}', [LevelController::class, 'show']);    // menampilkan detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']); // menampilkan halaman form edit level
        Route::put('/{id}', [LevelController::class, 'update']);  // menyimpan perubahan data level
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // menampilkan halaman form edit level
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);  // menyimpan perubahan data level
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // menampilkan halaman form edit level
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);  // menyimpan perubahan data level
        Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
        Route::get('import', [LevelController::class, 'import']);
        Route::post('import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('export_excel', [LevelController::class, 'export_excel']);
        Route::get('export_pdf', [LevelController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG'])->prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index']); //menampilkan halaman awal
        Route::post('/list', [KategoriController::class, 'list']); //menampilkan data user bentuk json / datatables
        Route::get('/create', [KategoriController::class, 'create']); // meanmpilkan bentuk form untuk tambah user
        Route::post('/', [KategoriController::class, 'store']); //menyimpan user data baru 
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // meanmpilkan bentuk form untuk tambah user ajax js 6 
        Route::post('/ajax', [KategoriController::class, 'store_ajax']); //menyimpan user data baru ajax js 6 
        Route::get('/{id}', [KategoriController::class, 'show']); // menampilkan detil user
        Route::get('/{id}/edit', [KategoriController::class, 'edit']); // menampilkan halaman form edit user
        Route::put('/{id}', [KategoriController::class, 'update']); // menyimpan perubahan data user 
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // menampilkan halaman form edit user ajax js 6 
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // menyimpan perubahan data user ajax js 6 
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // menghapus data user ajax js 6 
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // menghapus data user ajax js 6 
        Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data user 
        Route::get('/import', [KategoriController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); // ajax form import excel 
        Route::get('export_excel', [KategoriController::class, 'export_excel']);
        Route::get('export_pdf', [KategoriController::class, 'export_pdf']);
    });


    Route::middleware(['authorize:MNG,ADM'])->prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index']); //menampilkan halaman awal
        Route::post('/list', [SupplierController::class, 'list']); //menampilkan data user bentuk json / datatables
        Route::get('/create', [SupplierController::class, 'create']); // meanmpilkan bentuk form untuk tambah user
        Route::post('/', [SupplierController::class, 'store']); //menyimpan user data baru 
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // meanmpilkan bentuk form untuk tambah user
        Route::post('/ajax', [SupplierController::class, 'store_ajax']); //menyimpan user data baru 
        Route::get('/{id}', [SupplierController::class, 'show']); // menampilkan detil user
        Route::get('/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit user
        Route::put('/{id}', [SupplierController::class, 'update']); // menyimpan perubahan data user 
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // menampilkan halaman form edit user
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data user 
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // menghapus data user 
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // menghapus data user 
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data user 
        Route::get('/import', [SupplierController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); // ajax form import excel 
        Route::get('export_excel', [SupplierController::class, 'export_excel']);
        Route::get('export_pdf', [SupplierController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM, MNG'])->prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::post('/', [BarangController::class, 'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
        Route::delete('/{id}', [BarangController::class, 'destroy']);
        Route::get('import', [BarangController::class, 'import']);
        Route::post('import_ajax', [BarangController::class, 'import_ajax']);
        Route::get('export_excel', [BarangController::class, 'export_excel']);
        Route::get('export_pdf', [BarangController::class, 'export_pdf']);
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('/profile', [UserController::class, 'profile_page']);
        Route::post('/update_picture', [UserController::class, 'update_picture']);
    });
});
