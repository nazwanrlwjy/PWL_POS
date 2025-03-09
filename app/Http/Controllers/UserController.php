<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = UserModel::create([
            'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => Hash::make('12345'),
            'level_id' => 2,
        ]);
        
        $user->username = 'manager12';
        
        $user->save();
        
        $user->wasChanged(); // true
        $user->wasChanged('username'); // true
        $user->wasChanged(['username', 'level_id']); // true
        $user->wasChanged('nama'); // false
        dd($user->wasChanged(['nama', 'username'])); // true
        
        //JS4_2.5
        // $user = UserModel::create([
        //     'username' => 'manager55',
        //     'nama' => 'Manager55',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);
        
        // $user->username = 'manager56';
        
        // $user->isDirty(); // true
        // $user->isDirty('username'); // true
        // $user->isDirty('nama'); // false
        // $user->isDirty(['nama', 'username']); // true
        
        // $user->isClean(); // false
        // $user->isClean('username'); // false
        // $user->isClean('nama'); // true
        // $user->isClean(['nama', 'username']); // false
        
        // $user->save();
        
        // $user->isDirty(); // false
        // $user->isClean(); // true
        // dd($user->isDirty());
        
        
        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager',
        //         'nama' => 'Manager',
        //     ],
        // );

        // return view('user', ['data' => $user]);

        // //JS4_2.3
        // $user = UserModel::where('level_id', 2)->count(); // Menghitung jumlah pengguna
        // return view('user', ['data' => $user]); // Mengirim data ke view
        
        // $user = UserModel::where('level_id', 2)->count();
        // dd($user);
        // return view('user', ['data' => $user]);

        //JS4_2.2
        // $user = UserModel::where('username', 'manager9')->firstOrFail();
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOrFail(1);
        // return view('user', ['data' => $user]);

        //JS4_PRAK2_10
        // $user = UserModel::findOr(20, ['username', 'nama'], function () {
        //     abort(404);
        // });

        // return view('user', ['data' => $user]);
        //JS4_PRAK2_8
        // $user = UserModel::findOr(1, ['username', 'nama'], function () {
        //     abort(404);
        // });

        // return view('user', ['data' => $user]);

        //JS4_PRAK 2.1_6
        // $user = UserModel::firstWhere('level_id', 1);
        // return view('user', ['data' => $user]);

        //JS4_PRAK 2.1_4
        // $user = UserModel::where('level_id', 1)->first();
        // return view('user', ['data' => $user]);

        // JS4_PRAK1_2
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];

        // UserModel::create($data);

        // $user = UserModel::all();

        //Prak 4_2.1
        // $user = UserModel::find(1);
        // return view('user', ['data' => $user]);


        // // ini JS 3
        // // Perbaiki dan update data user berdasarkan username
        // UserModel::where('username', 'customer-1')->update($data); // update data user

        // // coba akses model UserModel
        // $user = UserModel::all(); // ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);
    }
}
